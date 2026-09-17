<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class UntimedTextResult
{
    /** @param list<array{line:int,text:string}> $lines */
    public function __construct(
        public array $lines,
        public int $characterCount,
    ) {
    }
}
