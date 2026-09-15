<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Controller;

use NoviqLabs\TxtToSrt\Http\Request;
use NoviqLabs\TxtToSrt\Http\Response;

final class HealthController
{
    public function __invoke(Request $request): Response
    {
        return Response::json(['status' => 'ok', 'service' => 'txt-to-srt-ui']);
    }
}
