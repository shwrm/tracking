<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use Shwrm\Tracking\Enum\DPDBusinessCodes;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\AccessToCarrierDenied;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\AbstractAdapter;
use Webit\DPDClient\DPDInfoServices\Client;
use Webit\DPDClient\DPDInfoServices\Common\Exception\AccessDeniedException;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventDataV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventsResponseV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\EventsSelectTypeEnum;

class DPDAdapter extends AbstractAdapter
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function name(): string
    {
        return 'dpd';
    }

    public function fetchStatus(array $parameters): string
    {
        try {
            $events = $this->client
                ->getEventsForWaybillV1($parameters['trackingCode'], EventsSelectTypeEnum::all(), 'PL');
        } catch (AccessDeniedException $exception) {
            throw new AccessToCarrierDenied($this->name(), $exception);
        }

        if (0 === $events->count()) {
            throw new UnknownStatusException();
        }

        if ($this->isDelivered($events)) {
            return DPDBusinessCodes::DELIVERED;
        }

        $lastEvent = \current($events->eventsList());

        if ($this->isRedirected($lastEvent)) {
            return $this->handleRedirect($lastEvent);
        }

        $status = DPDBusinessCodes::mapBusinessCodeToStatus($lastEvent->businessCode());

        if (null === $status) {
            throw new UnknownStatusException();
        }

        return $status;
    }

    public function resolveStatus(string $adapterStatus): string
    {
        $map = [
            DPDBusinessCodes::NEW       => Status::NEW,
            DPDBusinessCodes::DELIVERED => Status::DELIVERED,
            DPDBusinessCodes::RETURNED  => Status::RETURNED,
            DPDBusinessCodes::ERROR     => Status::ERROR,
            DPDBusinessCodes::SENT      => Status::SENT,
        ];

        if (false === isset($map[$adapterStatus])) {
            throw new UnknownStatusException();
        }

        return $map[$adapterStatus];
    }

    /**
     * @param CustomerEventsResponseV3|CustomerEventV3[] $events
     *
     * @return bool
     */
    private function isDelivered(CustomerEventsResponseV3 $events): bool
    {
        $delivered = DPDBusinessCodes::delivered();
        foreach ($events as $event) {
            if (\array_key_exists($event->businessCode(), $delivered)) {
                return true;
            }
        }

        return false;
    }

    private function isRedirected(CustomerEventV3 $event): bool
    {
        return \array_key_exists($event->businessCode(), DpdBusinessCodes::redirected());
    }

    private function handleRedirect(CustomerEventV3 $event)
    {
        /** @var CustomerEventDataV3 $eventData */
        $eventData = \current($event->eventDataList());

        if (null === $eventData) {
            throw new UnknownStatusException();
        }

        return $this->fetchStatus(['trackingCode' => $eventData->value()]);
    }
}
