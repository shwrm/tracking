<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Integrations\Adapters;

use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Adapters\UPSAdapter;
use Shwrm\Tracking\Integrations\Clients\ClientFactoryResolver;
use Shwrm\Tracking\Integrations\Clients\Factories\UPSClientFactory;
use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;
use Shwrm\Tracking\ValueObject\ValidationError;
use Ups\Tracking;

class UPSAdapterTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $parameters, ValidationErrors $expected)
    {
        $factory = $this->createMock(UPSClientFactory::class);
        $adapter = new UPSAdapter($factory);

        $this->assertEquals($expected, $adapter->validate($parameters));
    }

    public function validateDataProvider()
    {
        return [
            [['trackingCode' => 'test'], new ValidationErrors()],
            [[], new ValidationErrors([new ValidationError('trackingCode', 'is missing')])],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFetchStatus(string $type, string $code, string $expectedStatus)
    {
        $object = json_decode(json_encode(['Package' => ['Activity' => [['Status' => ['StatusType' => ['Code' => $type], 'StatusCode' => ['Code' => $code]]]]]]));
        $client = $this->createMock(Tracking::class);
        $client
            ->expects($this->once())
            ->method('track')
            ->willReturn($object)
        ;
        $factory = $this->createMock(UPSClientFactory::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($client)
        ;
        $adapter = new UPSAdapter($factory);

        $this->assertEquals($expectedStatus, $adapter->fetchStatus('1', ['trackingCode' => 'test']));
    }

    public function dataProvider()
    {
        return [
            ['X', 'X', 'X'],
            ['D', 'X', 'D'],
            ['D', 'ZP', Status::SENT],
            ['D', 'DL', Status::RETURNED],
        ];
    }

    public function testFetchUnknownStatusException()
    {
        $object = json_decode(json_encode([]));
        $client = $this->createMock(Tracking::class);
        $client
            ->expects($this->once())
            ->method('track')
            ->willReturn($object)
        ;
        $factory = $this->createMock(UPSClientFactory::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($client)
        ;
        $adapter = new UPSAdapter($factory);

        $this->expectException(UnknownStatusException::class);

        $adapter->fetchStatus('1', ['trackingCode' => 'test']);
    }

    /**
     * @dataProvider resolveStatusDataProvider
     */
    public function testResolveStatus(string $adapterStatus, string $expectedStatus)
    {
        $factory = $this->createMock(UPSClientFactory::class);
        $adapter = new UPSAdapter($factory);

        $this->assertEquals($expectedStatus, $adapter->resolveStatus($adapterStatus));
    }

    public function resolveStatusDataProvider()
    {
        return [
            ['M', Status::NEW],
            ['X', Status::NEW],
            ['X1', Status::SENT],
            ['X2', Status::SENT],
            ['X3', Status::SENT],
            ['I', Status::SENT],
            ['P', Status::SENT],
            ['D', Status::DELIVERED],
            ['RS', Status::DELIVERED],
            [Status::NEW, Status::NEW],
            [Status::SENT, Status::SENT],
            [Status::DELIVERED, Status::DELIVERED],
            [Status::RETURNED, Status::RETURNED],
        ];
    }

    public function testResolveUnknownStatus()
    {
        $factory = $this->createMock(UPSClientFactory::class);
        $adapter = new UPSAdapter($factory);

        $this->expectException(UnknownStatusException::class);

        $adapter->resolveStatus('unknown');
    }
}
