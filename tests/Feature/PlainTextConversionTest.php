<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Support\InputMode;
use PHPUnit\Framework\TestCase;

final class PlainTextConversionTest extends TestCase
{
    public function testGeneratesTimingAndPreservesUnicode(): void
    {
        $srt = (new ConvertTextContent())->convert("First line\n\nCafé 世界", false, InputMode::Auto, new AutoTimingOptions());
        self::assertStringContainsString('00:00:00,000 --> 00:00:03,000', $srt);
        self::assertStringContainsString('00:00:03,000 --> 00:00:06,000', $srt);
        self::assertStringContainsString('Café 世界', $srt);
    }
}
