<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class PathResolver
{
    /** @return list<string> */
    public function txtFiles(string $input, bool $recursive): array
    {
        if (is_file($input)) {
            if (strtolower(pathinfo($input, PATHINFO_EXTENSION)) !== 'txt') {
                throw new InvalidArgumentException('Input file must have a .txt extension.');
            }
            return [$input];
        }
        if (!is_dir($input)) {
            throw new InvalidArgumentException("Input path does not exist: {$input}");
        }

        $files = [];
        if ($recursive) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($input, RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $item) {
                if ($item->isFile() && strtolower($item->getExtension()) === 'txt') {
                    $files[] = $item->getPathname();
                }
            }
        } else {
            foreach (scandir($input) ?: [] as $name) {
                $candidate = $input . DIRECTORY_SEPARATOR . $name;
                if (is_file($candidate) && strtolower(pathinfo($candidate, PATHINFO_EXTENSION)) === 'txt') {
                    $files[] = $candidate;
                }
            }
        }
        sort($files, SORT_STRING);
        return $files;
    }

    public function outputFor(string $file, string $input, ?string $outputDirectory): string
    {
        $name = pathinfo($file, PATHINFO_FILENAME) . '.srt';
        if ($outputDirectory === null) {
            return dirname($file) . DIRECTORY_SEPARATOR . $name;
        }
        $relativeDirectory = is_dir($input)
            ? ltrim(str_replace(realpath($input) ?: $input, '', dirname(realpath($file) ?: $file)), DIRECTORY_SEPARATOR)
            : '';
        $directory = rtrim($outputDirectory, DIRECTORY_SEPARATOR);
        if ($relativeDirectory !== '') {
            $directory .= DIRECTORY_SEPARATOR . $relativeDirectory;
        }
        return $directory . DIRECTORY_SEPARATOR . $name;
    }
}
