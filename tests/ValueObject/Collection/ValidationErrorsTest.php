<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\ValueObject\Collection;

use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\ValueObject\Collection\ValidationErrors;
use Shwrm\Tracking\ValueObject\ValidationError;

class ValidationErrorsTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testToString(string $expectedMsg, ValidationError ...$errors)
    {
        $this->assertEquals($expectedMsg, (new ValidationErrors($errors))->__toString());
    }

    public function dataProvider()
    {
        return [
            [''],
            ['Problem #1 - trackingCode is missing', new ValidationError('trackingCode', 'is missing')],
            ['Problem #1 - trackingCode is missing, Problem #2 - phone is empty', new ValidationError('trackingCode', 'is missing'), new ValidationError('phone', 'is empty')],
        ];
    }
}
