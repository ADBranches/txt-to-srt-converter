<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Formatter;

use NoviqLabs\TxtToSrt\Domain\Caption;

final class SrtFormatter
{
    /** @param list<Caption> $captions */
    public function format(array $captions): string
    {
        $blocks = [];
        foreach ($captions as $offset => $caption) {
            $blocks[] = sprintf("%d\n%s --> %s\n%s", $offset + 1, $caption->start, $caption->end, $caption->text);
        }

        return implode("\n\n", $blocks) . "\n";
    }
}
