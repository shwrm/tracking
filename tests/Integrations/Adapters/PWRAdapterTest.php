<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Integrations\Adapters;

use mirolabs\ruch\client\Client;
use mirolabs\ruch\client\Enum\PackStatus;
use mirolabs\ruch\client\Enum\PackStatus as EnumPackStatus;
use mirolabs\ruch\client\Type\PackStatusResponse;
use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Adapters\PWRAdapter;
use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;
use Shwrm\Tracking\ValueObject\ValidationError;

class PWRAdapterTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $parameters, ValidationErrors $expected)
    {
        $client  = $this->createMock(Client::class);
        $adapter = new PWRAdapter($client);

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
        $response = new PackStatusResponse();
        $response->setCode(100);
        $response->setStatus(PackStatus::RECEIVED);
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getPackStatus')
            ->willReturn($response)
        ;
        $adapter = new PWRAdapter($client);

        $this->assertEquals(PackStatus::RECEIVED, $adapter->fetchStatus(['trackingCode' => 'test']));
    }

    public function testFetchUnknownStatus()
    {
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('getPackStatus')
            ->willReturn(new PackStatusResponse())
        ;
        $adapter = new PWRAdapter($client);

        $this->expectException(UnknownStatusException::class);

        $adapter->fetchStatus(['trackingCode' => 'test']);
    }

    /**
     * @dataProvider resolveStatusDataProvider
     */
    public function testResolveStatus(string $adapterStatus, string $expectedStatus)
    {
        $client  = $this->createMock(Client::class);
        $adapter = new PWRAdapter($client);

        $this->assertEquals($expectedStatus, $adapter->resolveStatus($adapterStatus));
    }

    public function resolveStatusDataProvider()
    {
        return [
            [EnumPackStatus::IN_REGIONAL_SORTING_PLANT, Status::SENT],
            [EnumPackStatus::IN_TRANSPORT_TO_SC, Status::SENT],
            [EnumPackStatus::IN_TRANSPORT_FROM_SENDER, Status::NEW],
            [EnumPackStatus::CANCELED, Status::ERROR],
            [EnumPackStatus::POSTED_IN_POP, Status::SENT],
            [EnumPackStatus::IN_TRANSPORT_FROM_POP, Status::SENT],
            [EnumPackStatus::IN_CENTRAL_SORTING_PLANT, Status::SENT],
            [EnumPackStatus::IN_CENTRAL_SORTING, Status::SENT],
            [EnumPackStatus::IN_TRANSPORT_TO_EXPEDITION, Status::SENT],
            [EnumPackStatus::IN_EXPEDITION, Status::SENT],
            [EnumPackStatus::IN_TRANSPORT_TO_POP, Status::SENT],
            [EnumPackStatus::IN_POP, Status::SENT],
            [EnumPackStatus::IN_POP_SMS, Status::SENT],
            [EnumPackStatus::EXPIRED, Status::SENT],
            [EnumPackStatus::RETURN_EXPIRED, Status::SENT],
            [EnumPackStatus::RETURN_WRONG_POP, Status::SENT],
            [EnumPackStatus::COMPLAINT, Status::ERROR],
            [EnumPackStatus::RETURN_TO_EXPEDITION, Status::SENT],
            [EnumPackStatus::RETURN_TO_SORTING, Status::SENT],
            [EnumPackStatus::RETURN_TO_SENDER, Status::RETURNED],
            [EnumPackStatus::RECEIVED_BY_CUSTOMER, Status::DELIVERED],
            [EnumPackStatus::RECEIVED, Status::DELIVERED],
        ];
    }

    public function testResolveUnknownStatus()
    {
        $client  = $this->createMock(Client::class);
        $adapter = new PWRAdapter($client);

        $this->expectException(UnknownStatusException::class);

        $adapter->resolveStatus('unknown');
    }
}
