<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Validation;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Support\Timecode;

final class TimestampValidator
{
    public function normalize(string $value, int $line, string $field, bool $repair): string
    {
        $value = trim($value);
        if ($repair) {
            $value = str_replace('.', ',', $value);
        }
        $pattern = $repair
            ? '/^(?:(\d+):)?([0-5]?\d):([0-5]?\d)(?:,(\d{1,3}))?$/'
            : '/^(\d{2,}):([0-5]\d):([0-5]\d),(\d{3})$/';
        if (preg_match($pattern, $value, $m) !== 1) {
            throw ConversionException::forLine($line, "invalid {$field} timestamp '{$value}'.");
        }
        if ($repair) {
            $hours = $m[1] === '' ? 0 : (int)$m[1];
            $minutes = (int)$m[2];
            $seconds = (int)$m[3];
            $milliseconds = (int)str_pad($m[4] ?? '0', 3, '0');
            return (new Timecode((($hours * 3600 + $minutes * 60 + $seconds) * 1000) + $milliseconds))->format();
        }
        return $value;
    }
}
