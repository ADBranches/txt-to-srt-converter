<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Parser\LyricTxtParser;
use NoviqLabs\TxtToSrt\Repair\SafeInputRepairer;
use NoviqLabs\TxtToSrt\Support\FileEncoding;
use NoviqLabs\TxtToSrt\Support\PathResolver;

final readonly class BatchConverter
{
    public function __construct(
        private ConvertTxtToSrt $converter = new ConvertTxtToSrt(),
        private PathResolver $paths = new PathResolver(),
        private FileEncoding $encoding = new FileEncoding(),
        private SafeInputRepairer $repairer = new SafeInputRepairer(),
        private LyricTxtParser $parser = new LyricTxtParser(),
    ) {}

    /** @return array{passed:int,skipped:int,failed:int,messages:list<string>} */
    public function run(string $input, ?string $outputDirectory, bool $recursive, bool $overwrite, bool $repair, bool $dryRun): array
    {
        $summary = ['passed' => 0, 'skipped' => 0, 'failed' => 0, 'messages' => []];
        foreach ($this->paths->txtFiles($input, $recursive) as $file) {
            $output = $this->paths->outputFor($file, $input, $outputDirectory);
            try {
                if (!$overwrite && is_file($output)) {
                    $summary['skipped']++;
                    $summary['messages'][] = "SKIP {$file}: output exists";
                    continue;
                }
                if ($dryRun) {
                    $content = $this->encoding->readUtf8($file);
                    if ($repair) $content = $this->repairer->repair($content);
                    $this->parser->parse($content, $repair);
                    $summary['passed']++;
                    $summary['messages'][] = "PASS {$file}: dry-run validation";
                    continue;
                }
                $directory = dirname($output);
                if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
                    throw new ConversionException("Unable to create output directory: {$directory}");
                }
                $this->converter->convert($file, $output, $overwrite, $repair);
                $summary['passed']++;
                $summary['messages'][] = "PASS {$file} -> {$output}";
            } catch (\Throwable $error) {
                $summary['failed']++;
                $summary['messages'][] = "FAIL {$file}: {$error->getMessage()}";
            }
        }
        return $summary;
    }
}
