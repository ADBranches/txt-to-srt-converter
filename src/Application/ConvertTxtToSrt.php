<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Formatter\SrtFormatter;
use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use NoviqLabs\TxtToSrt\Support\ConversionResult;
use NoviqLabs\TxtToSrt\Support\FileEncoding;

final readonly class ConvertTxtToSrt
{
    public function __construct(
        private FileEncoding $encoding = new FileEncoding(),
        private LyricTxtParser $parser = new LyricTxtParser(),
        private SrtFormatter $formatter = new SrtFormatter(),
    ) {
    }

    public function convert(string $input, ?string $output = null, bool $overwrite = false): ConversionResult
    {
        $output ??= preg_replace('/\.[^.]+$/', '', $input) . '.srt';
        if ($output === null || $output === $input) {
            throw new ConversionException('Unable to determine a safe output path.');
        }
        if (file_exists($output) && !$overwrite) {
            throw new ConversionException("Output already exists: {$output}. Use --overwrite to replace it.");
        }

        $captions = $this->parser->parse($this->encoding->readUtf8($input));
        $srt = $this->formatter->format($captions);
        $directory = dirname($output);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new ConversionException("Output directory is missing or not writable: {$directory}");
        }

        $temporary = tempnam($directory, '.srt-');
        if ($temporary === false || file_put_contents($temporary, $srt, LOCK_EX) === false) {
            throw new ConversionException('Unable to write temporary output file.');
        }
        if (!rename($temporary, $output)) {
            @unlink($temporary);
            throw new ConversionException("Unable to finalize output file: {$output}");
        }

        return new ConversionResult($input, $output, count($captions), strlen($srt));
    }
}
