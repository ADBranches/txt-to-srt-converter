<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Http\SecurityHeaders;
use PHPUnit\Framework\TestCase;

final class SecurityHeadersTest extends TestCase
{
    public function testRequiredHeadersAreApplied(): void
    {
        $response = (new SecurityHeaders())->apply(Response::html('ok'));
        self::assertArrayHasKey('Content-Security-Policy', $response->headers);
        self::assertSame('nosniff', $response->headers['X-Content-Type-Options']);
        self::assertSame('DENY', $response->headers['X-Frame-Options']);
        self::assertSame('no-store', $response->headers['Cache-Control']);
    }
}
