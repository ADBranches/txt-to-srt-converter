<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

use NoviqLabs\TxtToSrt\Exception\ConversionException;

final class FileEncoding
{
    public function readUtf8(string $file): string
    {
        if (!is_file($file) || !is_readable($file)) {
            throw new ConversionException("Input file is missing or unreadable: {$file}");
        }

        $content = file_get_contents($file);
        if ($content === false) {
            throw new ConversionException("Unable to read input file: {$file}");
        }

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        if (!mb_check_encoding($content, 'UTF-8')) {
            throw new ConversionException('Input file must be valid UTF-8.');
        }

        return str_replace(["\r\n", "\r"], "\n", $content);
    }
}
