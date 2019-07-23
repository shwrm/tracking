<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Clients\Config;

use Shwrm\Tracking\Exception\MissingCarrierConfigException;
use Shwrm\Tracking\Exception\MissingCarrierConfigIdException;

class ConfigResolver
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function resolve(string $clientName, string $id): array
    {
        if (false === isset($this->config[$clientName])) {
            throw new MissingCarrierConfigException($clientName);
        }

        if (false === isset($this->config[$clientName][$id])) {
            throw new MissingCarrierConfigIdException($clientName, $id);
        }

        return $this->config[$clientName][$id];
    }
}
