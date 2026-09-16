<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\UploadedFile;
use NoviqLabs\TxtToSrt\Validation\UploadValidator;
use PHPUnit\Framework\TestCase;

final class UploadValidatorTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upload-test-') ?: '';
        file_put_contents($this->file, "00:00:01,000 | 00:00:02,000 | Test\n");
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    public function testAcceptsValidTextUpload(): void
    {
        $validator = new UploadValidator(1048576, ['txt'], ['text/plain', 'text/csv']);
        $validator->validate(new UploadedFile('valid.txt', $this->file, filesize($this->file), UPLOAD_ERR_OK, 'text/plain'));
        self::addToAssertionCount(1);
    }

    public function testRejectsUnsafeExtension(): void
    {
        $this->expectException(HttpException::class);
        (new UploadValidator(1048576, ['txt'], ['text/plain', 'text/csv']))
            ->validate(new UploadedFile('../../unsafe.php', $this->file, filesize($this->file), UPLOAD_ERR_OK, 'text/plain'));
    }

    public function testRejectsOversizedUpload(): void
    {
        $this->expectException(HttpException::class);
        (new UploadValidator(5, ['txt'], ['text/plain']))
            ->validate(new UploadedFile('large.txt', $this->file, 6, UPLOAD_ERR_OK, 'text/plain'));
    }
}
