<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Repair;

final class SafeInputRepairer
{
    public function repair(string $content): string
    {
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }
        return str_replace(["\r\n", "\r"], "\n", $content);
    }
}
