<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Http\UploadedFile;
use NoviqLabs\TxtToSrt\Support\BatchConversionResult;
use NoviqLabs\TxtToSrt\Support\SafeFilename;
use NoviqLabs\TxtToSrt\Validation\UploadValidator;

final readonly class ConvertUploadedBatch
{
    public function __construct(
        private ConvertTextContent $converter,
        private UploadValidator $validator,
        private SafeFilename $filenames,
        private int $maxCount,
        private int $maxTotalBytes,
    ) {
    }

    /** @param list<UploadedFile> $files */
    public function convert(array $files, bool $repair): BatchConversionResult
    {
        if ($files === [] || count($files) > $this->maxCount) {
            throw new \InvalidArgumentException('Select between 1 and ' . $this->maxCount . ' TXT files.');
        }
        $total = array_sum(array_map(static fn (UploadedFile $file): int => $file->size, $files));
        if ($total > $this->maxTotalBytes) {
            throw new \InvalidArgumentException('The batch exceeds the total upload limit.');
        }

        $rows = [];
        $successful = [];
        $used = [];
        $passed = 0;
        $failed = 0;
        foreach ($files as $file) {
            try {
                $this->validator->validate($file);
                $content = file_get_contents($file->temporaryName);
                if ($content === false) {
                    throw new \RuntimeException('The uploaded file could not be read.');
                }
                $srt = $this->converter->convert($content, $repair);
                $name = $this->filenames->srt($file->name);
                $base = pathinfo($name, PATHINFO_FILENAME);
                $suffix = 2;
                while (isset($used[$name])) {
                    $name = $base . '-' . $suffix . '.srt';
                    $suffix++;
                }
                $used[$name] = true;
                $successful[$name] = $srt;
                $rows[] = ['name' => $file->name, 'status' => 'passed', 'message' => 'Validated and converted.', 'token' => null];
                $passed++;
            } catch (\Throwable $error) {
                $rows[] = ['name' => basename($file->name), 'status' => 'failed', 'message' => $error->getMessage(), 'token' => null];
                $failed++;
            }
        }
        return new BatchConversionResult($passed, 0, $failed, $rows, $successful);
    }
}
