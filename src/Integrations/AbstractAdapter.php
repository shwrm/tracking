<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations;

abstract class AbstractAdapter implements IntegrationAdapterInterface
{
    public function track(array $parameters): string
    {
        $adapterStatus = $this->fetchStatus($parameters);

        return $this->resolveStatus($adapterStatus);
    }

    abstract protected function fetchStatus(array $parameters): string;

    abstract protected function resolveStatus(string $adapterStatus): string;
}
