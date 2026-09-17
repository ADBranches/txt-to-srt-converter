<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Application\DetectTextTiming;
use NoviqLabs\TxtToSrt\Support\TextTimingState;
use PHPUnit\Framework\TestCase;

final class DetectTextTimingTest extends TestCase
{
    public function testDetectsTimestampedAndUntimedText(): void
    {
        $detector = new DetectTextTiming();
        self::assertSame(
            TextTimingState::Timestamped,
            $detector->detect('00:00:01,000 | 00:00:03,000 | Caption')
        );
        self::assertSame(
            TextTimingState::Untimed,
            $detector->detect("First line\nSecond line")
        );
    }
}
