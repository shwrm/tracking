<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Integrations\Adapters;

use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\Enum\DPDBusinessCodes;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\AccessToCarrierDenied;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Adapters\DPDAdapter;
use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;
use Shwrm\Tracking\ValueObject\ValidationError;
use Webit\DPDClient\DPDInfoServices\Client;
use Webit\DPDClient\DPDInfoServices\Common\Exception\AccessDeniedException;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventsResponseV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventV3;

class DPDAdapterTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $parameters, ValidationErrors $expected)
    {
        $client  = $this->createMock(Client::class);
        $adapter = new DPDAdapter($client);

        $this->assertEquals($expected, $adapter->validate($parameters));
    }

    public function validateDataProvider()
    {
        return [
            [['trackingCode' => 'test'], new ValidationErrors()],
            [[], new ValidationErrors([new ValidationError('trackingCode', 'is missing')])],
        ];
    }

    public function testFetchStatus()
    {
        $event  = new CustomerEventV3(1, '120100', '1', '1', '1', '1', 'pl', '1', '1', 1);
        $events = new CustomerEventsResponseV3('test', [$event]);
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getEventsForWaybillV1')
            ->willReturn($events)
        ;
        $adapter = new DPDAdapter($client);

        $this->assertEquals(DPDBusinessCodes::SENT, $adapter->fetchStatus(['trackingCode' => 'test']));
    }

    public function testFetchUnknownStatus()
    {
        $events = new CustomerEventsResponseV3('test', []);
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getEventsForWaybillV1')
            ->willReturn($events)
        ;
        $adapter = new DPDAdapter($client);

        $this->expectException(UnknownStatusException::class);

        $adapter->fetchStatus(['trackingCode' => 'test']);
    }

    public function testFetchStatusThrowsAuthException()
    {
        $client  = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getEventsForWaybillV1')
            ->willThrowException(new AccessDeniedException());
        $adapter = new DPDAdapter($client);

        $this->expectException(AccessToCarrierDenied::class);
        $this->expectExceptionMessage('Access denied for: dpd');

        $adapter->fetchStatus(['trackingCode' => 'test']);
    }

    /**
     * @dataProvider resolveStatusDataProvider
     */
    public function testResolveStatus(string $adapterStatus, string $expectedStatus)
    {
        $client  = $this->createMock(Client::class);
        $adapter = new DPDAdapter($client);

        $this->assertEquals($expectedStatus, $adapter->resolveStatus($adapterStatus));
    }

    public function resolveStatusDataProvider()
    {
        return [
            [DPDBusinessCodes::NEW, Status::NEW],
            [DPDBusinessCodes::DELIVERED, Status::DELIVERED],
            [DPDBusinessCodes::RETURNED, Status::RETURNED],
            [DPDBusinessCodes::ERROR, Status::ERROR],
            [DPDBusinessCodes::SENT, Status::SENT],
        ];
    }

    public function testResolveUnknownStatus()
    {
        $client  = $this->createMock(Client::class);
        $adapter = new DPDAdapter($client);

        $this->expectException(UnknownStatusException::class);

        $adapter->resolveStatus('unknown');
    }
}
