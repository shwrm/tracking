<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use Shwrm\Tracking\Enum\DPDBusinessCodes;
use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Exception\AccessToCarrierDenied;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Clients\Factories\DPDClientFactory;
use Webit\DPDClient\DPDInfoServices\Common\Exception\AccessDeniedException;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventDataV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventsResponseV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\CustomerEventV3;
use Webit\DPDClient\DPDInfoServices\CustomerEvents\EventsSelectTypeEnum;

class DPDAdapter extends AbstractAdapter
{
    /** @var DPDClientFactory */
    private $clientFactory;

    const NAME = 'dpd';

    public function __construct(DPDClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function name(): string
    {
        return self::NAME;
    }

    public function fetchStatus(string $id, array $parameters): string
    {
        $client = $this->clientFactory->create($id);

        try {
            $events = $client
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
            return $this->handleRedirect($lastEvent, $id);
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

    private function handleRedirect(CustomerEventV3 $event, string $id)
    {
        /** @var CustomerEventDataV3 $eventData */
        $eventData = \current($event->eventDataList());

        if (null === $eventData) {
            throw new UnknownStatusException();
        }

        return $this->fetchStatus($id, ['trackingCode' => $eventData->value()]);
    }
}
