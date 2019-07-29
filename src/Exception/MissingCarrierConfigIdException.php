<?php declare(strict_types=1);

namespace Shwrm\Tracking\Exception;

class MissingCarrierConfigIdException extends \RuntimeException
{
    /** @var string */
    private $carrierName;

    /** @var string */
    private $integrationId;

    public function __construct(string $carrierName, string $integrationId)
    {
        $this->carrierName   = $carrierName;
        $this->integrationId = $integrationId;

        parent::__construct(sprintf('Missing integration id (%s) for carrier: %s', $integrationId, $carrierName));
    }

    public function getCarrierName(): string
    {
        return $this->carrierName;
    }

    public function getIntegrationId(): string
    {
        return $this->integrationId;
    }
}

