<?php

declare(strict_types=1);

return [
    'name' => 'Noviq TXT-to-SRT Converter',
    'target_version' => '1.1.0',
    'routes' => [
        'editor' => ['method' => 'GET', 'path' => '/'],
        'preview' => ['method' => 'POST', 'path' => '/preview'],
        'convert' => ['method' => 'POST', 'path' => '/convert'],
        'download' => ['method' => 'GET', 'path' => '/download'],
        'health' => ['method' => 'GET', 'path' => '/health'],
    ],
    'progressive_enhancement' => true,
    'wcag_target' => '2.2 AA',
    'states' => ['empty', 'loading', 'success', 'warning', 'error'],
];
