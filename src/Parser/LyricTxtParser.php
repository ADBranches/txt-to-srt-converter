<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Parser;

use NoviqLabs\TxtToSrt\Domain\Caption;
use NoviqLabs\TxtToSrt\Exception\ConversionException;

final class LyricTxtParser
{
    /** @return list<Caption> */
    public function parse(string $content): array
    {
        $captions = [];

        foreach (explode("\n", $content) as $offset => $rawLine) {
            $lineNumber = $offset + 1;
            $line = trim($rawLine);
            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\s*\|\s*/u', $line, 3);
            if ($parts === false || count($parts) !== 3) {
                throw ConversionException::forLine($lineNumber, 'expected START | END | TEXT.');
            }

            [$start, $end, $text] = array_map('trim', $parts);
            if ($text === '') {
                throw ConversionException::forLine($lineNumber, 'caption text cannot be empty.');
            }

            $start = $this->normalizeTimecode($start, $lineNumber, 'start');
            $end = $this->normalizeTimecode($end, $lineNumber, 'end');
            if ($this->milliseconds($end) <= $this->milliseconds($start)) {
                throw ConversionException::forLine($lineNumber, 'end time must be greater than start time.');
            }

            $previous = $captions[array_key_last($captions)] ?? null;
            if ($previous !== null && $this->milliseconds($start) < $this->milliseconds($previous->end)) {
                throw ConversionException::forLine($lineNumber, sprintf('start time overlaps caption from line %d.', $previous->sourceLine));
            }

            $captions[] = new Caption($start, $end, $text, $lineNumber);
        }

        if ($captions === []) {
            throw new ConversionException('Input contains no subtitle entries.');
        }

        return $captions;
    }

    private function normalizeTimecode(string $value, int $line, string $field): string
    {
        $value = str_replace('.', ',', $value);
        if (preg_match('/^(?:(\d{1,}):)?([0-5]?\d):([0-5]?\d)(?:,(\d{1,3}))?$/', $value, $matches) !== 1) {
            throw ConversionException::forLine($line, "invalid {$field} timestamp '{$value}'.");
        }

        $hours = isset($matches[1]) && $matches[1] !== '' ? (int) $matches[1] : 0;
        $minutes = (int) $matches[2];
        $seconds = (int) $matches[3];
        $fraction = $matches[4] ?? '0';
        $milliseconds = (int) str_pad($fraction, 3, '0');

        return sprintf('%02d:%02d:%02d,%03d', $hours, $minutes, $seconds, $milliseconds);
    }

    private function milliseconds(string $timecode): int
    {
        preg_match('/^(\d+):(\d{2}):(\d{2}),(\d{3})$/', $timecode, $parts);
        return (((int) $parts[1] * 3600 + (int) $parts[2] * 60 + (int) $parts[3]) * 1000) + (int) $parts[4];
    }
}
