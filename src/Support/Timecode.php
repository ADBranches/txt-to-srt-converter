<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class Timecode
{
    public function __construct(public int $milliseconds)
    {
    }

    public static function fromCanonical(string $value): self
    {
        preg_match('/^(\d+):(\d{2}):(\d{2}),(\d{3})$/', $value, $p);
        return new self((((int)$p[1] * 3600 + (int)$p[2] * 60 + (int)$p[3]) * 1000) + (int)$p[4]);
    }

    public function format(): string
    {
        $hours = intdiv($this->milliseconds, 3600000);
        $remaining = $this->milliseconds % 3600000;
        $minutes = intdiv($remaining, 60000);
        $remaining %= 60000;
        $seconds = intdiv($remaining, 1000);
        return sprintf('%02d:%02d:%02d,%03d', $hours, $minutes, $seconds, $remaining % 1000);
    }
}
