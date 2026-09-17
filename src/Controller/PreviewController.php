<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Application\PreviewConversion;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;

final readonly class PreviewController
{
    public function __construct(private PreviewConversion $preview)
    {
    }
    public function __invoke(Request $request): Response
    {
        $content = is_string($request->post['lyrics'] ?? null) ? $request->post['lyrics'] : '';
        try {
            $result = $this->preview->preview($content, isset($request->post['repair']), \NoviqLabs\TxtToSrt\Support\InputMode::tryFrom((string) ($request->post['input_mode'] ?? 'auto')) ?? \NoviqLabs\TxtToSrt\Support\InputMode::Auto, new \NoviqLabs\TxtToSrt\Support\AutoTimingOptions((int) round(((float) ($request->post['start_offset'] ?? 0)) * 1000), (int) round(((float) ($request->post['caption_duration'] ?? 3)) * 1000), (int) round(((float) ($request->post['caption_gap'] ?? 0)) * 1000)));
        } catch (\Throwable $error) {
            throw new HttpException(422, $error->getMessage());
        } ob_start();
        $preview = $result;
        require dirname(__DIR__, 2) . '/resources/views/components/caption-preview.php';
        return Response::html((string)ob_get_clean());
    }
}
