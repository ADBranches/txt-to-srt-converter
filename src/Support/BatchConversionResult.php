<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class BatchConversionResult
{
    /**
     * @param list<array{name:string,status:string,message:string,token:?string}> $files
     * @param array<string, string> $successfulFiles
     */
    public function __construct(
        public int $passed,
        public int $skipped,
        public int $failed,
        public array $files,
        public array $successfulFiles,
    ) {
    }
}
