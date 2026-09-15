<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Domain;

final readonly class Caption
{
    public function __construct(
        public string $start,
        public string $end,
        public string $text,
        public int $sourceLine,
    ) {
    }
}
