<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;

final class ArchiveDownloadController
{
    public function __invoke(Request $request): Response
    {
        $token = is_string($request->query['token'] ?? null) ? $request->query['token'] : '';
        $content = $_SESSION['_archives'][$token] ?? null;
        if (!is_string($content)) {
            throw new HttpException(404, 'The archive is missing or expired.');
        }
        unset($_SESSION['_archives'][$token]);
        return new Response($content, 200, ['Content-Type' => 'application/zip', 'Content-Disposition' => 'attachment; filename="txt-to-srt-batch.zip"', 'Content-Length' => (string) strlen($content)]);
    }
}
