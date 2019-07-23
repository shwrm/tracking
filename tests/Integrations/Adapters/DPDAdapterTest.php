<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Integrations\Adapters;

use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\Enum\DPDBusinessCodes;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\AccessToCarrierDenied;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Adapters\DPDAdapter;
use Shwrm\Tracking\Integrations\Clients\Factories\DPDClientFactory;
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
        $factory = $this->createMock(DPDClientFactory::class);
        $adapter = new DPDAdapter($factory);

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
        $factory = $this->createMock(DPDClientFactory::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($client)
        ;
        $adapter = new DPDAdapter($factory);

        $this->assertEquals(DPDBusinessCodes::SENT, $adapter->fetchStatus('1', ['trackingCode' => 'test']));
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
        $factory = $this->createMock(DPDClientFactory::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($client)
        ;
        $adapter = new DPDAdapter($factory);

        $this->expectException(UnknownStatusException::class);

        $adapter->fetchStatus('1', ['trackingCode' => 'test']);
    }

    public function testFetchStatusThrowsAuthException()
    {
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getEventsForWaybillV1')
            ->willThrowException(new AccessDeniedException())
        ;
        $factory = $this->createMock(DPDClientFactory::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($client)
        ;
        $adapter = new DPDAdapter($factory);

        $this->expectException(AccessToCarrierDenied::class);
        $this->expectExceptionMessage('Access denied for: dpd');

        $adapter->fetchStatus('1', ['trackingCode' => 'test']);
    }

    /**
     * @dataProvider resolveStatusDataProvider
     */
    public function testResolveStatus(string $adapterStatus, string $expectedStatus)
    {
        $factory = $this->createMock(DPDClientFactory::class);
        $adapter = new DPDAdapter($factory);

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
        $factory = $this->createMock(DPDClientFactory::class);
        $adapter = new DPDAdapter($factory);

        $this->expectException(UnknownStatusException::class);

        $adapter->resolveStatus('unknown');
    }
}
