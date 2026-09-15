<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Validation\TimestampValidator;
use PHPUnit\Framework\TestCase;

final class TimestampValidatorTest extends TestCase
{
    public function testStrictRejectsMinorVariant(): void
    {
        $this->expectException(ConversionException::class);
        (new TimestampValidator())->normalize('1:02.5', 4, 'start', false);
    }
    public function testRepairNormalizesMinorVariant(): void
    {
        self::assertSame('00:01:02,500', (new TimestampValidator())->normalize('1:02.5', 1, 'start', true));
    }
    public function testRejectsNumericRange(): void
    {
        $this->expectException(ConversionException::class);
        (new TimestampValidator())->normalize('00:61:00,000', 1, 'start', true);
    }
}
