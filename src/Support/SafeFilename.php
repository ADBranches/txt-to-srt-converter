<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final class SafeFilename
{
    public function srt(string $name): string
    {
        $base = pathinfo($name, PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_-]+/', '-', $base) ?? 'subtitles';
        $base = trim($base, '-');
        return ($base !== '' ? $base : 'subtitles') . '.srt';
    }
}
