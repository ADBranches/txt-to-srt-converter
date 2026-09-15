<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Validation;

use NoviqLabs\TxtToSrt\Exception\ConversionException;

final class SrtValidator
{
    public function validate(string $srt, int $expectedCount): void
    {
        if (!mb_check_encoding($srt, 'UTF-8') || str_starts_with($srt, "\xEF\xBB\xBF")) {
            throw new ConversionException('Generated SRT is not BOM-free UTF-8.');
        }
        $blocks = preg_split('/\n\n/', trim($srt));
        if ($blocks === false || count($blocks) !== $expectedCount) {
            throw new ConversionException('Generated SRT block count is invalid.');
        }
        foreach ($blocks as $offset => $block) {
            $lines = explode("\n", $block);
            if (($lines[0] ?? '') !== (string)($offset + 1)) {
                throw new ConversionException('Generated SRT indexes are not sequential.');
            }
            if (preg_match('/^\d{2,}:\d{2}:\d{2},\d{3} --> \d{2,}:\d{2}:\d{2},\d{3}$/', $lines[1] ?? '') !== 1) {
                throw new ConversionException('Generated SRT contains an invalid timing line.');
            }
            if (trim(implode("\n", array_slice($lines, 2))) === '') {
                throw new ConversionException('Generated SRT contains empty caption text.');
            }
        }
    }
}
