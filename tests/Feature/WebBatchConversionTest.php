<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use NoviqLabs\TxtToSrt\Application\ConvertUploadedBatch;
use NoviqLabs\TxtToSrt\Http\UploadedFile;
use NoviqLabs\TxtToSrt\Support\SafeFilename;
use NoviqLabs\TxtToSrt\Validation\UploadValidator;
use PHPUnit\Framework\TestCase;

final class WebBatchConversionTest extends TestCase
{
    public function testTruthfulMixedSummaryAndCollisionSafeNames(): void
    {
        $fixtures = dirname(__DIR__) . '/Fixtures/uploads/';
        $service = new ConvertUploadedBatch(new ConvertTextContent(), new UploadValidator(1048576, ['txt'], ['text/plain', 'text/csv']), new SafeFilename(), 20, 10485760);
        $files = [
            new UploadedFile('shared.txt', $fixtures . 'valid-ui-lyrics.txt', filesize($fixtures . 'valid-ui-lyrics.txt'), UPLOAD_ERR_OK, 'text/plain'),
            new UploadedFile('shared.txt', $fixtures . 'unicode-ui-lyrics.txt', filesize($fixtures . 'unicode-ui-lyrics.txt'), UPLOAD_ERR_OK, 'text/plain'),
            new UploadedFile('invalid.txt', $fixtures . 'invalid-ui-lyrics.txt', filesize($fixtures . 'invalid-ui-lyrics.txt'), UPLOAD_ERR_OK, 'text/plain'),
        ];
        $result = $service->convert($files, false);
        self::assertSame(3, $result->passed);
        self::assertSame(0, $result->failed);
        self::assertSame(['shared.srt', 'shared-2.srt', 'invalid.srt'], array_keys($result->successfulFiles));
    }

    public function testCountLimitIsEnforced(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ConvertUploadedBatch(new ConvertTextContent(), new UploadValidator(10, ['txt'], ['text/plain']), new SafeFilename(), 1, 10))->convert([], false);
    }
}
