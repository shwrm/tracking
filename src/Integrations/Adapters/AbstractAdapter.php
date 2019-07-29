<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;
use Shwrm\Tracking\ValueObject\ValidationError;

abstract class AbstractAdapter implements IntegrationAdapterInterface
{
    public function track(string $id, array $parameters): string
    {
        $adapterStatus = $this->fetchStatus($id, $parameters);

        return $this->resolveStatus($adapterStatus);
    }

    public function validate(array $parameters): ValidationErrors
    {
        if (false === isset($parameters['trackingCode'])) {
            $errors[] = new ValidationError('trackingCode', 'is missing');
        }

        return new ValidationErrors($errors ?? []);
    }

    abstract protected function fetchStatus(string $id, array $parameters): string;

    abstract protected function resolveStatus(string $adapterStatus): string;
}
