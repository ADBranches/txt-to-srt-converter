<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\InspectUntimedText;
use PHPUnit\Framework\TestCase;

final class UntimedTextPreviewTest extends TestCase
{
    public function testInspectionPreservesUnicodeAndIgnoresBlankLines(): void
    {
        $result = (new InspectUntimedText())->inspect("First line\n\nCafé 世界");
        self::assertCount(2, $result->lines);
        self::assertSame('Café 世界', $result->lines[1]['text']);
        self::assertSame(3, $result->lines[1]['line']);
    }
}
