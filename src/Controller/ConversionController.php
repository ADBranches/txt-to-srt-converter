<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Application\ConvertTextContent;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Http\UploadedFile;
use NoviqLabs\TxtToSrt\Support\SafeFilename;
use NoviqLabs\TxtToSrt\Validation\UploadValidator;

final readonly class ConversionController
{
    public function __construct(private ConvertTextContent $converter, private UploadValidator $uploads, private SafeFilename $filenames)
    {
    }
    public function __invoke(Request $request): Response
    {
        $content = is_string($request->post['lyrics'] ?? null) ? $request->post['lyrics'] : '';
        $sourceName = 'subtitles.txt';
        if (isset($request->files['lyrics_file']) && is_array($request->files['lyrics_file']) && (($request->files['lyrics_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)) {
            $file = UploadedFile::fromArray($request->files['lyrics_file']);
            $this->uploads->validate($file);
            $loaded = file_get_contents($file->temporaryName);
            if ($loaded === false) {
                throw new HttpException(422, 'The upload could not be read.');
            }
            $content = $loaded;
            $sourceName = $file->name;
        }
        if (trim($content) === '') {
            throw new HttpException(422, 'Paste lyrics or upload a TXT file.');
        }
        try {
            $srt = $this->converter->convert($content, isset($request->post['repair']));
        } catch (\Throwable $error) {
            throw new HttpException(422, $error->getMessage());
        }
        $token = bin2hex(random_bytes(24));
        $_SESSION['_downloads'][$token] = ['content' => $srt, 'filename' => $this->filenames->srt($sourceName)];
        return Response::html('<!doctype html><html lang="en"><body><main><h1>Conversion complete</h1><p>Your validated SRT is ready.</p><a href="/download?token=' . $token . '">Download SRT</a><p><a href="/">Convert another file</a></p></main></body></html>');
    }
}
