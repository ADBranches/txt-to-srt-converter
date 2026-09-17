<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Application\DetectInputMode;
use NoviqLabs\TxtToSrt\Support\InputMode;
use PHPUnit\Framework\TestCase;

final class DetectInputModeTest extends TestCase
{
    public function testDetectsBothModes(): void
    {
        $detector = new DetectInputMode();
        self::assertSame(InputMode::Timestamped, $detector->detect('00:00:01,000 | 00:00:02,000 | Text'));
        self::assertSame(InputMode::PlainText, $detector->detect("First line\nSecond line"));
    }
}
