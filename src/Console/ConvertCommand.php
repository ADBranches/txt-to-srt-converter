<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Console;

use NoviqLabs\TxtToSrt\Application\BatchConverter;

final readonly class ConvertCommand
{
    public function __construct(private BatchConverter $batch = new BatchConverter()) {}

    /** @param list<string> $arguments */
    public function run(array $arguments): int
    {
        $options = ['overwrite'=>false,'repair'=>false,'recursive'=>false,'dry-run'=>false,'output-dir'=>null];
        $positionals = [];
        for ($i = 0; $i < count($arguments); $i++) {
            $arg = $arguments[$i];
            if ($arg === '--help' || $arg === '-h') { $this->help(); return 0; }
            if (in_array($arg, ['--overwrite','--repair','--recursive','--dry-run'], true)) { $options[ltrim($arg, '-')] = true; continue; }
            if ($arg === '--output-dir') {
                if (!isset($arguments[++$i])) { fwrite(STDERR, "Missing value for --output-dir\n"); return 2; }
                $options['output-dir'] = $arguments[$i]; continue;
            }
            if (str_starts_with($arg, '-')) { fwrite(STDERR, "Unknown option: {$arg}\n"); return 2; }
            $positionals[] = $arg;
        }
        if (count($positionals) !== 1) { $this->help(STDERR); return 2; }
        try {
            $summary = $this->batch->run($positionals[0], $options['output-dir'], $options['recursive'], $options['overwrite'], $options['repair'], $options['dry-run']);
        } catch (\Throwable $error) {
            fwrite(STDERR, "FAIL: {$error->getMessage()}\n"); return 1;
        }
        $theme = TerminalTheme::forStream(STDOUT);
        foreach ($summary['messages'] as $message) fwrite(STDOUT, $theme->status($message) . PHP_EOL);
        fwrite(STDOUT, sprintf("Summary: passed=%d skipped=%d failed=%d\n", $summary['passed'], $summary['skipped'], $summary['failed']));
        return $summary['failed'] > 0 ? 1 : 0;
    }

    private function help($stream = STDOUT): void
    {
        fwrite($stream, "Usage: bin/txt-to-srt INPUT [--output-dir DIR] [--recursive] [--overwrite] [--repair] [--dry-run] [--help]\n");
    }
}
