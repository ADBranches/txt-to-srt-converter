<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use PHPUnit\Framework\TestCase;

final class LyricTxtParserTest extends TestCase
{
    public function testParsesBlankLinesUnicodeAndTimestampVariants(): void
    {
        $captions = (new LyricTxtParser())->parse("0:01.5 | 00:03,25 | Héllo, 世界!\n\n00:03,250 | 00:05,000 | Next | lyric", true);
        self::assertCount(2, $captions);
        self::assertSame('00:00:01,500', $captions[0]->start);
        self::assertSame('00:00:03,250', $captions[0]->end);
        self::assertSame('Héllo, 世界!', $captions[0]->text);
        self::assertSame('Next | lyric', $captions[1]->text);
    }

    public function testReportsExactMalformedLine(): void
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Line 3:');
        (new LyricTxtParser())->parse("00:00:01,000 | 00:00:02,000 | good\n\nbad row");
    }
}
