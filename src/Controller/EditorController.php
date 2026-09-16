<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;

final readonly class EditorController
{
    public function __construct(private CsrfTokenManager $csrf)
    {
    }
    public function __invoke(Request $request): Response
    {
        $csrf = $this->csrf->token();
        $lyrics = "00:00:01,000 | 00:00:04,000 | First lyric line\n00:00:04,000 | 00:00:07,000 | Second lyric line\n";
        ob_start();
        require dirname(__DIR__, 2) . '/resources/views/editor.php';
        return Response::html((string) ob_get_clean());
    }
}
