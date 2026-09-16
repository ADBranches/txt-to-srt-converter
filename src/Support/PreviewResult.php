<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Support;

final readonly class PreviewResult
{
    /** @param list<array{index:int,start:string,end:string,duration:string,text:string,line:int}> $captions */
    public function __construct(public array $captions, public int $characterCount)
    {
    }
}
