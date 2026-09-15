<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Http;

final readonly class UploadedFile
{
    public function __construct(
        public string $name,
        public string $temporaryName,
        public int $size,
        public int $error,
        public string $type,
    ) {
    }

    /** @param array<string, mixed> $file */
    public static function fromArray(array $file): self
    {
        return new self(
            (string) ($file['name'] ?? ''),
            (string) ($file['tmp_name'] ?? ''),
            (int) ($file['size'] ?? 0),
            (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE),
            (string) ($file['type'] ?? ''),
        );
    }
}
