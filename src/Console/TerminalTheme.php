<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Console;

final readonly class TerminalTheme
{
    public function __construct(private bool $enabled) {}

    public static function forStream($stream): self
    {
        return new self(function_exists('stream_isatty') && @stream_isatty($stream));
    }

    public function status(string $line): string
    {
        if (!$this->enabled) return $line;
        $code = str_starts_with($line, 'PASS') ? '38;2;69;190;166'
            : (str_starts_with($line, 'FAIL') ? '31' : '38;2;0;174;239');
        return "\033[{$code}m{$line}\033[0m";
    }
}
