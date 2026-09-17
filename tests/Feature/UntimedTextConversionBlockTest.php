<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use NoviqLabs\TxtToSrt\Exception\ConversionException;
use PHPUnit\Framework\TestCase;

final class UntimedTextConversionBlockTest extends TestCase
{
    public function testUntimedTextNeverProducesFinalSrt(): void
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('expected START | END | TEXT');
        (new ConvertTextContent())->convert("First line\nSecond line");
    }
}
