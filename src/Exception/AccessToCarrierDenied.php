<?php declare(strict_types=1);

namespace Shwrm\Tracking\Exception;

class AccessToCarrierDenied extends \RuntimeException
{
    /** @var string */
    private $carrierName;

    public function __construct(string $carrierName, \Throwable $previousException)
    {
        $this->carrierName = $carrierName;
        parent::__construct('Access denied for: ' . $carrierName, 0, $previousException);
    }
}
