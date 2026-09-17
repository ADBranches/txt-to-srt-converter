<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Validation\AutoTimingValidator;
use PHPUnit\Framework\TestCase;

final class AutoTimingValidatorTest extends TestCase
{
    public function testRejectsZeroDuration(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new AutoTimingValidator())->validate(new AutoTimingOptions(0, 0, 0));
    }
}
