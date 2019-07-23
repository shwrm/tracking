<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Clients\Factories;

use Shwrm\Tracking\Integrations\Clients\Config\ConfigResolver;
use Shwrm\Tracking\Integrations\Clients\IntegrationClientFactoryInterface;
use Webit\DPDClient\DPDInfoServices\Client\ClientFactory;
use Webit\DPDClient\DPDInfoServices\Common\AuthDataV1;

class DPDClientFactory implements IntegrationClientFactoryInterface
{
    /** @var ConfigResolver */
    private $configResolver;

    public function __construct(ConfigResolver $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function create(string $id): object
    {
        $config = $this->configResolver->resolve('dpd', $id);

        $clientFactory = new ClientFactory();
        $client        = $clientFactory
            ->create(new AuthDataV1($config['login'], $config['password'], $config['channel']));

        return $client;
    }
}
