<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

use RuntimeException;
use ZipArchive;

final class ZipArchiveBuilder
{
    /** @param array<string, string> $files */
    public function build(array $files): string
    {
        $temporary = tempnam(sys_get_temp_dir(), 'txt-to-srt-batch-');
        if ($temporary === false) {
            throw new RuntimeException('Unable to create archive storage.');
        }

        $archive = new ZipArchive();
        if ($archive->open($temporary, ZipArchive::OVERWRITE) !== true) {
            unlink($temporary);
            throw new RuntimeException('Unable to open batch archive.');
        }

        foreach ($files as $name => $content) {
            $archive->addFromString($name, $content);
        }
        $archive->close();

        $content = file_get_contents($temporary);
        unlink($temporary);
        if ($content === false) {
            throw new RuntimeException('Unable to read batch archive.');
        }
        return $content;
    }
}
