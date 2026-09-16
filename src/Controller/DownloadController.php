<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Http\DownloadResponse;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;

final class DownloadController
{
    public function __invoke(Request $request): Response
    {
        $token = is_string($request->query['token'] ?? null) ? $request->query['token'] : '';
        $item = $_SESSION['_downloads'][$token] ?? null;
        if (!is_array($item) || !is_string($item['content'] ?? null) || !is_string($item['filename'] ?? null)) {
            throw new HttpException(404, 'The download is missing or expired.');
        }
        unset($_SESSION['_downloads'][$token]);
        return DownloadResponse::srt($item['content'], $item['filename']);
    }
}
