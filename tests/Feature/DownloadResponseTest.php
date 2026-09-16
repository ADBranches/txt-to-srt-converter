<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Http\DownloadResponse;
use PHPUnit\Framework\TestCase;

final class DownloadResponseTest extends TestCase
{
    public function testSrtDownloadHeadersAndFilename(): void
    {
        $response = DownloadResponse::srt('content', 'safe.srt');
        self::assertSame(200, $response->status);
        self::assertSame('application/x-subrip; charset=UTF-8', $response->headers['Content-Type']);
        self::assertSame('attachment; filename="safe.srt"', $response->headers['Content-Disposition']);
    }
}
