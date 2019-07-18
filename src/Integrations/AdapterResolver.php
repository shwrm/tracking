<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations;

use Shwrm\Tracking\Exception\NotImplementedAdapterException;

class AdapterResolver
{
    /** @var IntegrationAdapterInterface[] */
    private $adapters = [];

    public function __construct(iterable $adapters)
    {
        foreach ($adapters as $adapter) {
            $this->addAdapter($adapter);
        }
    }

    private function addAdapter(IntegrationAdapterInterface $adapter): void
    {
        $this->adapters[$adapter->name()] = $adapter;
    }

    public function resolve(string $name): IntegrationAdapterInterface
    {
        if (false === isset($this->adapters[$name])) {
            throw new NotImplementedAdapterException();
        }

        return $this->adapters[$name];
    }
}
