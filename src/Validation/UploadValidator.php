<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Validation;

use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\UploadedFile;

final readonly class UploadValidator
{
    /**
     * @param list<string> $extensions
     * @param list<string> $mimes
     */
    public function __construct(private int $maxBytes, private array $extensions, private array $mimes)
    {
    }
    public function validate(UploadedFile $file): void
    {
        if ($file->error !== UPLOAD_ERR_OK) {
            throw new HttpException(422, 'The TV file upload failed.');
        }
        if ($file->size < 1 || $file->size > $this->maxBytes) {
            throw new HttpException(422, 'The upload exceeds the allowed size.');
        }
        $ext = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->extensions, true)) {
            throw new HttpException(422, 'Only .txt files are allowed.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->temporaryName);
        if (!is_string($mime) || !in_array($mime, $this->mimes, true)) {
            throw new HttpException(422, 'The upload is not plain text.');
        }
    }
}
