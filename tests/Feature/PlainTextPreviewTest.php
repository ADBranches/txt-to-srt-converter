<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\PreviewConversion;
use NoviqLabs\TxtToSrt\Support\AutoTimingOptions;
use NoviqLabs\TxtToSrt\Support\InputMode;
use PHPUnit\Framework\TestCase;

final class PlainTextPreviewTest extends TestCase
{
    public function testPreviewUsesEstimatedTiming(): void
    {
        $result = (new PreviewConversion())->preview('Plain caption', false, InputMode::PlainText, new AutoTimingOptions());
        self::assertSame('00:00:00,000', $result->captions[0]['start']);
        self::assertSame('00:00:03,000', $result->captions[0]['end']);
    }
}
