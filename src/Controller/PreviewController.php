<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Application\DetectTextTiming;
use NoviqLabs\TxtToSrt\Application\InspectUntimedText;
use NoviqLabs\TxtToSrt\Application\PreviewConversion;
use NoviqLabs\TxtToSrt\Http\HttpException;
use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;
use NoviqLabs\TxtToSrt\Support\TextTimingState;

final readonly class PreviewController
{
    public function __construct(
        private PreviewConversion $preview,
        private DetectTextTiming $detector = new DetectTextTiming(),
        private InspectUntimedText $untimedInspector = new InspectUntimedText(),
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $content = is_string($request->post['lyrics'] ?? null)
            ? $request->post['lyrics']
            : '';

        try {
            if ($this->detector->detect($content) === TextTimingState::Untimed) {
                $untimed = $this->untimedInspector->inspect($content);
                ob_start();
                require dirname(__DIR__, 2) . '/resources/views/components/untimed-text-preview.php';
                return Response::html((string) ob_get_clean());
            }

            $result = $this->preview->preview(
                $content,
                isset($request->post['repair'])
            );
        } catch (\Throwable $error) {
            throw new HttpException(422, $error->getMessage());
        }

        ob_start();
        $preview = $result;
        require dirname(__DIR__, 2) . '/resources/views/components/caption-preview.php';
        return Response::html((string) ob_get_clean());
    }
}
