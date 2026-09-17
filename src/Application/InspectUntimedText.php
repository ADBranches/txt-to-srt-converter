<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Application;

use InvalidArgumentException;
use NoviqLabs\TxtToSrt\Support\UntimedTextResult;

final class InspectUntimedText
{
    public function inspect(string $content): UntimedTextResult
    {
        if (!mb_check_encoding($content, 'UTF-8')) {
            throw new InvalidArgumentException('Input must be valid UTF-8.');
        }

        $sourceLines = preg_split('/\R/u', $content) ?: [];
        $lines = [];

        foreach ($sourceLines as $index => $sourceLine) {
            $text = trim($sourceLine);
            if ($text === '') {
                continue;
            }

            $lines[] = [
                'line' => $index + 1,
                'text' => $text,
            ];
        }

        if ($lines === []) {
            throw new InvalidArgumentException(
                'Untimed text must contain at least one non-empty line.'
            );
        }

        return new UntimedTextResult($lines, mb_strlen($content));
    }
}
