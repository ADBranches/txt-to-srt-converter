<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final class Session
{
    /** @param array<string, mixed> $config */
    public function start(array $config, bool $https): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        ini_set('session.use_strict_mode', ($config['use_strict_mode'] ?? true) ? '1' : '0');
        session_set_cookie_params([
            'httponly' => (bool) ($config['cookie_httponly'] ?? true),
            'secure' => (bool) ($config['cookie_secure_from_https'] ?? true) && $https,
            'samesite' => (string) ($config['cookie_samesite'] ?? 'Strict'),
            'path' => '/',
        ]);
        session_start();
    }
}
