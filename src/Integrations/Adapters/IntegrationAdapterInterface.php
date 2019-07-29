<?php declare(strict_types=1);

namespace Shwrm\Tracking\Integrations\Adapters;

use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;

interface IntegrationAdapterInterface
{
    public function name(): string;

    public function validate(array $parameters): ValidationErrors;

    public function track(string $id, array $parameters): string;
}
