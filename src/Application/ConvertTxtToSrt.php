<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Formatter\SrtFormatter;
use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use NoviqLabs\TxtToSrt\Repair\SafeInputRepairer;
use NoviqLabs\TxtToSrt\Support\ConversionResult;
use NoviqLabs\TxtToSrt\Support\FileEncoding;
use NoviqLabs\TxtToSrt\Validation\SrtValidator;

final readonly class ConvertTxtToSrt
{
    public function __construct(private FileEncoding $encoding = new FileEncoding(), private SafeInputRepairer $repairer = new SafeInputRepairer(), private LyricTxtParser $parser = new LyricTxtParser(), private SrtFormatter $formatter = new SrtFormatter(), private SrtValidator $validator = new SrtValidator()) {}

    public function convert(string $input, ?string $output = null, bool $overwrite = false, bool $repair = false): ConversionResult
    {
        $output ??= preg_replace('/\.[^.]+$/', '', $input) . '.srt';
        if ($output === null || $output === $input) throw new ConversionException('Unable to determine a safe output path.');
        if (file_exists($output) && !$overwrite) throw new ConversionException("Output already exists: {$output}. Use --overwrite to replace it.");
        $content = $this->encoding->readUtf8($input);
        if ($repair) $content = $this->repairer->repair($content);
        $captions = $this->parser->parse($content, $repair);
        $srt = $this->formatter->format($captions);
        $this->validator->validate($srt, count($captions));
        $directory = dirname($output);
        if (!is_dir($directory) || !is_writable($directory)) throw new ConversionException("Output directory is missing or not writable: {$directory}");
        $temporary = tempnam($directory, '.srt-');
        if ($temporary === false || file_put_contents($temporary, $srt, LOCK_EX) === false) throw new ConversionException('Unable to write temporary output file.');
        if (!rename($temporary, $output)) { @unlink($temporary); throw new ConversionException("Unable to finalize output file: {$output}"); }
        return new ConversionResult($input, $output, count($captions), strlen($srt));
    }
}
