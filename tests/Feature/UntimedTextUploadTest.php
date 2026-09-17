<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\DetectTextTiming;
use NoviqLabs\TxtToSrt\Application\InspectUntimedText;
use NoviqLabs\TxtToSrt\Support\TextTimingState;
use PHPUnit\Framework\TestCase;

final class UntimedTextUploadTest extends TestCase
{
    public function testUntimedTxtContentCanBeLoadedForInspection(): void
    {
        $content = "Opening line\nSecond line\n";
        self::assertSame(
            TextTimingState::Untimed,
            (new DetectTextTiming())->detect($content)
        );
        self::assertCount(2, (new InspectUntimedText())->inspect($content)->lines);
    }
}
