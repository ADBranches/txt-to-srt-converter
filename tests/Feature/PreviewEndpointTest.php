<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Application\PreviewConversion;
use NoviqLabs\TxtToSrt\Controller\PreviewController;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use PHPUnit\Framework\TestCase;

final class PreviewEndpointTest extends TestCase
{
    public function testValidUnicodePreviewIsNotConversionSuccess(): void
    {
        $response = (new PreviewController(new PreviewConversion()))(new Request('POST', '/preview', [], [
            'lyrics' => "00:00:01,000 | 00:00:03,000 | Café 世界!\n",
        ]));
        self::assertStringContainsString('Preview only, not yet converted', $response->body);
        self::assertStringContainsString('Café 世界!', $response->body);
        self::assertStringNotContainsString('Download SRT', $response->body);
    }

    public function testMalformedRowReportsLineAndFails(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Line 1:');
        (new PreviewController(new PreviewConversion()))(new Request('POST', '/preview', [], ['lyrics' => '00:00:01,000 | malformed row']));
    }
}
