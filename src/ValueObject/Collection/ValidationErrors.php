<?php declare(strict_types=1);

namespace Shwrm\Tracking\ValueObject\Collection;

use Shwrm\Tracking\ValueObject\ValidationError;

class ValidationErrors
{
    /** @var ValidationError[] */
    private $errors = [];

    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
    }

    public function isEmpty(): bool
    {
        return empty($this->errors);
    }

    public function __toString()
    {
        for ($i = 0; $i < count($this->errors); $i++) {
            $error = $this->errors[$i];
            $msg[] = sprintf('Problem #%d - %s %s', $i + 1, $error->getFieldName(), $error->getError());
        }

        return implode(', ', $msg ?? []);
    }
}
