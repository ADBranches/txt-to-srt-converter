<?php

declare(strict_types=1);

return [
    'uploads' => [
        'max_single_bytes' => 1_048_576,
        'max_batch_count' => 20,
        'max_batch_total_bytes' => 10_485_760,
        'allowed_extensions' => ['txt'],
        'allowed_mime_types' => ['text/plain'],
    ],
    'session' => [
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'cookie_secure_from_https' => true,
        'use_strict_mode' => true,
    ],
    'csrf' => [
        'required_for_state_changes' => true,
        'token_bytes' => 32,
    ],
    'downloads' => [
        'extension' => 'srt',
        'mime_type' => 'application/x-subrip',
        'disposition' => 'attachment',
        'one_time' => true,
    ],
    'temporary_files' => [
        'publicly_accessible' => false,
        'lifetime_seconds' => 900,
        'delete_after_response' => true,
    ],
];
