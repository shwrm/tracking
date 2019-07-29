<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Clients\Factories;

use mirolabs\ruch\client\Client;
use Shwrm\Tracking\Integrations\Clients\Config\ConfigResolver;
use Shwrm\Tracking\Integrations\Clients\IntegrationClientFactoryInterface;

class PWRClientFactory implements IntegrationClientFactoryInterface
{
    /** @var ConfigResolver */
    private $configResolver;

    public function __construct(ConfigResolver $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function create(string $id): object
    {
        $config = $this->configResolver->resolve('pwr', $id);

        return new Client($config['partnerId'], $config['partnerKey'], $config['environment']);
    }
}
