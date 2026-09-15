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
        $token = htmlspecialchars($this->csrf->token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return Response::html('<!doctype html><html lang="en"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>Noviq TXT-to-SRT Converter</title></head><body><main>'
            . '<h1>Noviq TXT-to-SRT Converter</h1><p>Secure web interface foundation is ready.</p>'
            . '<form method="post" action="/preview"><input type="hidden" name="_csrf" value="'
            . $token . '"><button type="submit">Preview foundation</button></form>'
            . '</main></body></html>');
    }
}
