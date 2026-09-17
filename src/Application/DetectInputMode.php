<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use NoviqLabs\TxtToSrt\Support\InputMode;

final class DetectInputMode
{
    public function detect(string $content): InputMode
    {
        $lines = preg_split('/\R/u', $content) ?: [];
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            return preg_match('/^\s*\d+:\d{2}:\d{2}[,.]\d{1,3}\s*\|/', $line) === 1
                ? InputMode::Timestamped
                : InputMode::PlainText;
        }
        return InputMode::PlainText;
    }
}
