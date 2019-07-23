<?php declare(strict_types=1);

namespace Shwrm\Tracking\Exception;

class MissingCarrierConfigIdException extends \RuntimeException
{
    /** @var string */
    private $carrierName;

    public function __construct(string $carrierName, string $id)
    {
        $this->carrierName = $carrierName;
        parent::__construct(sprintf('Missing integration id (%s) for carrier: %s', $id, $carrierName));
    }
}
