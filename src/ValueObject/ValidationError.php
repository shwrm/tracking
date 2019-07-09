<?php declare(strict_types=1);

namespace Shwrm\Tracking\ValueObject;

class ValidationError
{
    /** @var string */
    private $fieldName;

    /** @var string */
    private $error;

    public function __construct(string $fieldName, string $error)
    {
        $this->fieldName = $fieldName;
        $this->error     = $error;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
