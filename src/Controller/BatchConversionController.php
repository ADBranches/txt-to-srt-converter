<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Application\ConvertUploadedBatch;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Http\UploadedFile;
use NoviqLabs\TxtToSrt\Support\BatchConversionResult;
use NoviqLabs\TxtToSrt\Support\ZipArchiveBuilder;

final readonly class BatchConversionController
{
    public function __construct(
        private ConvertUploadedBatch $batch,
        private ZipArchiveBuilder $archives,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $raw = $request->files['batch_files'] ?? null;

        if (!is_array($raw) || !is_array($raw['name'] ?? null)) {
            throw new HttpException(422, 'Select one or more TXT files.');
        }

        $files = [];
        foreach ($raw['name'] as $index => $name) {
            $files[] = new UploadedFile(
                (string) $name,
                (string) ($raw['tmp_name'][$index] ?? ''),
                (int) ($raw['size'][$index] ?? 0),
                (int) ($raw['error'][$index] ?? UPLOAD_ERR_NO_FILE),
                (string) ($raw['type'][$index] ?? ''),
            );
        }

        try {
            $initialResult = $this->batch->convert(
                $files,
                isset($request->post['repair'])
            );
        } catch (\Throwable $error) {
            throw new HttpException(422, $error->getMessage());
        }

        $rows = $initialResult->files;
        $successfulRowIndex = 0;

        foreach ($initialResult->successfulFiles as $name => $content) {
            $token = bin2hex(random_bytes(24));
            $_SESSION['_downloads'][$token] = [
                'content' => $content,
                'filename' => $name,
            ];

            while (
                isset($rows[$successfulRowIndex])
                && $rows[$successfulRowIndex]['status'] !== 'passed'
            ) {
                $successfulRowIndex++;
            }

            if (isset($rows[$successfulRowIndex])) {
                $rows[$successfulRowIndex]['token'] = $token;
                $successfulRowIndex++;
            }
        }

        $result = new BatchConversionResult(
            $initialResult->passed,
            $initialResult->skipped,
            $initialResult->failed,
            $rows,
            $initialResult->successfulFiles,
        );

        $archiveToken = null;
        if ($result->successfulFiles !== []) {
            $archiveToken = bin2hex(random_bytes(24));
            $_SESSION['_archives'][$archiveToken] = $this->archives->build(
                $result->successfulFiles
            );
        }

        ob_start();
        require dirname(__DIR__, 2) . '/resources/views/batch.php';

        return Response::html((string) ob_get_clean());
    }
}
