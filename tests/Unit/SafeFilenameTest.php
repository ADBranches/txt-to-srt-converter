<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Support\SafeFilename;
use PHPUnit\Framework\TestCase;

final class SafeFilenameTest extends TestCase
{
    public function testNormalizesUnsafeNamesAndTraversal(): void
    {
        $filenames = new SafeFilename();
        self::assertSame('lyrics.srt', $filenames->srt('lyrics.txt'));
        self::assertSame('unsafe-file.srt', $filenames->srt('../../unsafe file.txt'));
        self::assertSame('subtitles.srt', $filenames->srt('.txt'));
    }
}
