<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class ConversionResult
{
    public function __construct(
        public string $inputFile,
        public string $outputFile,
        public int $captionCount,
        public int $bytesWritten,
    ) {
    }
}
