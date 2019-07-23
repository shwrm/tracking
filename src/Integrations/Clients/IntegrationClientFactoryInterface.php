<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Clients;

interface IntegrationClientFactoryInterface
{
    public function create(string $id): object;
}
