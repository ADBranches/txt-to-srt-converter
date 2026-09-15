<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final class SecurityHeaders
{
    public function apply(Response $response): Response
    {
        return new Response($response->body, $response->status, array_merge([
            'Content-Security-Policy' => "default-src 'self'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'",
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'no-referrer',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Cache-Control' => 'no-store',
        ], $response->headers));
    }
}
