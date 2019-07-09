<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use mirolabs\ruch\client\Client as PWRClient;
use mirolabs\ruch\client\Enum\PackStatus as EnumPackStatus;
use mirolabs\ruch\client\Type\PackStatus;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\AbstractAdapter;

class PWRAdapter extends AbstractAdapter
{
    /** @var PWRClient */
    private $client;

    public function __construct(PWRClient $client)
    {
        $this->client = $client;
    }

    public function name(): string
    {
        return 'pwr';
    }

    public function fetchStatus(array $parameters): string
    {
        $packStatus = new PackStatus();
        $packStatus->setPackCode($parameters['trackingCode']);

        $packStatusResponse = $this->client->getPackStatus($packStatus);
        if (null === $packStatusResponse->getCode()) {
            throw new UnknownStatusException();
        }

        return (string)$packStatusResponse->getStatus();
    }

    public function resolveStatus(string $adapterStatus): string
    {
        $map = [
            EnumPackStatus::IN_REGIONAL_SORTING_PLANT  => Status::SENT,
            EnumPackStatus::IN_TRANSPORT_TO_SC         => Status::SENT,
            EnumPackStatus::IN_TRANSPORT_FROM_SENDER   => Status::NEW,
            EnumPackStatus::CANCELED                   => Status::ERROR,
            EnumPackStatus::POSTED_IN_POP              => Status::SENT,
            EnumPackStatus::IN_TRANSPORT_FROM_POP      => Status::SENT,
            EnumPackStatus::IN_CENTRAL_SORTING_PLANT   => Status::SENT,
            EnumPackStatus::IN_CENTRAL_SORTING         => Status::SENT,
            EnumPackStatus::IN_TRANSPORT_TO_EXPEDITION => Status::SENT,
            EnumPackStatus::IN_EXPEDITION              => Status::SENT,
            EnumPackStatus::IN_TRANSPORT_TO_POP        => Status::SENT,
            EnumPackStatus::IN_POP                     => Status::SENT,
            EnumPackStatus::IN_POP_SMS                 => Status::SENT,
            EnumPackStatus::EXPIRED                    => Status::SENT,
            EnumPackStatus::RETURN_EXPIRED             => Status::SENT,
            EnumPackStatus::RETURN_WRONG_POP           => Status::SENT,
            EnumPackStatus::COMPLAINT                  => Status::ERROR,
            EnumPackStatus::RETURN_TO_EXPEDITION       => Status::SENT,
            EnumPackStatus::RETURN_TO_SORTING          => Status::SENT,
            EnumPackStatus::RETURN_TO_SENDER           => Status::RETURNED,
            EnumPackStatus::RECEIVED_BY_CUSTOMER       => Status::DELIVERED,
            EnumPackStatus::RECEIVED                   => Status::DELIVERED,
        ];

        if (false === isset($map[(int)$adapterStatus])) {
            throw new UnknownStatusException();
        }

        return $map[(int)$adapterStatus];
    }
}
