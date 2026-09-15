<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final readonly class CsrfTokenManager
{
    public function __construct(private int $tokenBytes = 32)
    {
    }

    public function token(): string
    {
        if (!isset($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes($this->tokenBytes));
        }
        return $_SESSION['_csrf'];
    }

    public function verify(?string $candidate): bool
    {
        $expected = $_SESSION['_csrf'] ?? null;
        return is_string($expected) && is_string($candidate) && hash_equals($expected, $candidate);
    }
}
