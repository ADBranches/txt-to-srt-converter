<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

use RuntimeException;

final class TemporaryFileManager
{
    /** @var list<string> */
    private array $files = [];

    public function create(string $prefix = 'txt-to-srt-ui-'): string
    {
        $file = tempnam(sys_get_temp_dir(), $prefix);
        if ($file === false) {
            throw new RuntimeException('Unable to create a temporary file.');
        }
        $this->files[] = $file;
        return $file;
    }

    public function cleanup(): void
    {
        foreach ($this->files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        $this->files = [];
    }

    public function __destruct()
    {
        $this->cleanup();
    }
}
