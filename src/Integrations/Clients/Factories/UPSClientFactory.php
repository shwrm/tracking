<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Clients\Factories;

use Shwrm\Tracking\Integrations\Clients\Config\ConfigResolver;
use Shwrm\Tracking\Integrations\Clients\IntegrationClientFactoryInterface;
use Ups\Tracking;

class UPSClientFactory implements IntegrationClientFactoryInterface
{
    /** @var ConfigResolver */
    private $configResolver;

    public function __construct(ConfigResolver $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function create(string $id): object
    {
        $config = $this->configResolver->resolve('ups', $id);

        return new Tracking($config['accessKey'], $config['userId'], $config['password']);
    }
}
