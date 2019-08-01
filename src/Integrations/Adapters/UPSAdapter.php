<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use Shwrm\Tracking\Enum\Status;
use Shwrm\Tracking\Enum\UPSStatus;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Clients\Factories\UPSClientFactory;

class UPSAdapter extends AbstractAdapter
{
    const REQUEST_OPTION_LAST_ACTIVITY                         = 0;
    const REQUEST_OPTION_ALL_ACTIVITY                          = 1;
    const REQUEST_OPTION_POD_RECEIVERADDRESS_LAST_ACTIVITY     = 2;
    const REQUEST_OPTION_POD_RECEIVERADDRESS_ALL_ACTIVITY      = 3;
    const REQUEST_OPTION_POD_COD_LAST_ACTIVITY                 = 4;
    const REQUEST_OPTION_POD_COD_ALL_ACTIVITY                  = 5;
    const REQUEST_OPTION_POD_COD_RECEIVERADDRESS_LAST_ACTIVITY = 6;
    const REQUEST_OPTION_POD_COD_RECEIVERADDRESS_ALL_ACTIVITY  = 7;

    const ACCESS_POINT_CODES = ['2Q', 'ZP'];

    /** @var UPSClientFactory */
    private $clientFactory;

    public function __construct(UPSClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function name(): string
    {
        return 'ups';
    }

    public function fetchStatus(string $id, array $parameters): string
    {
        $client = $this->clientFactory->create($id);

        $result = $client->track($parameters['trackingCode'], self::REQUEST_OPTION_POD_COD_ALL_ACTIVITY);

        if (false === is_object($result) or false === isset($result->Package->Activity)) {
            throw new UnknownStatusException();
        }

        if (isset($result->Package->ReturnTo)) {
            return Status::RETURNED;
        }

        $activity = \current($result->Package->Activity);

        if (false === isset($activity->Status)) {
            throw new UnknownStatusException();
        }

        $type = $activity->Status->StatusType->Code;

        if ('D' === $type) {
            $code = $activity->Status->StatusCode->Code;
            if (in_array($code, self::ACCESS_POINT_CODES, true)) {
                return Status::SENT;
            }

            if ('DL' === $code) {
                return Status::RETURNED;
            }
        }

        return $type;
    }

    public function resolveStatus(string $adapterStatus): string
    {
        $map = [
            'M'  => Status::NEW,
            'X'  => Status::NEW,
            'X1' => Status::SENT,
            'X2' => Status::SENT,
            'X3' => Status::SENT,
            'I'  => Status::SENT,
            'P'  => Status::SENT,
            'D'  => Status::DELIVERED,
            'RS' => Status::DELIVERED,
        ];

        if (isset($map[$adapterStatus])) {
            return $map[$adapterStatus];
        }

        if (in_array($adapterStatus, Status::STATUSES, true)) {
            return $adapterStatus;
        }

        throw new UnknownStatusException();
    }
}
