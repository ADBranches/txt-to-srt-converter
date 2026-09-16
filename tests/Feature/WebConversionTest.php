<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use PHPUnit\Framework\TestCase;

final class WebConversionTest extends TestCase
{
    public function testUnicodeOutputMatchesFixedFixture(): void
    {
        $input = file_get_contents(dirname(__DIR__) . '/Fixtures/uploads/unicode-ui-lyrics.txt');
        self::assertIsString($input);
        $actual = (new ConvertTextContent())->convert($input);
        self::assertSame("1\n00:00:01,000 --> 00:00:03,000\nCafé, 世界, Webale nnyo!\n", $actual);
    }

    public function testInvalidUtf8IsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ConvertTextContent())->convert("\xC3\x28");
    }
}
