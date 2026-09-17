<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use PHPUnit\Framework\TestCase;

final class PlainTextBatchConversionTest extends TestCase
{
    public function testDefaultConverterHandlesPlainAndTimestampedInputs(): void
    {
        $converter = new ConvertTextContent();
        self::assertStringContainsString('Plain line', $converter->convert('Plain line'));
        self::assertStringContainsString('Timed line', $converter->convert('00:00:01,000 | 00:00:02,000 | Timed line'));
    }
}
