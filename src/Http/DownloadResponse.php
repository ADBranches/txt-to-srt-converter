<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final class DownloadResponse
{
    public static function srt(string $content, string $filename): Response
    {
        return new Response($content, 200, [
            'Content-Type' => 'application/x-subrip; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($content),
        ]);
    }
}
