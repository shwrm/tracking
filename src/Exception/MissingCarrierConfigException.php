<?php declare(strict_types=1);

namespace Shwrm\Tracking\Exception;

class MissingCarrierConfigException extends \RuntimeException
{
    /** @var string */
    private $carrierName;

    public function __construct(string $carrierName)
    {
        $this->carrierName = $carrierName;
        parent::__construct('Missing integration config for: ' . $carrierName);
    }
}
