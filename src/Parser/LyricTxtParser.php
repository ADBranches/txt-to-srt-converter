<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Parser;

use NoviqLabs\TxtToSrt\Domain\Caption;
use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Validation\CaptionValidator;
use NoviqLabs\TxtToSrt\Validation\TimestampValidator;

final readonly class LyricTxtParser
{
    public function __construct(private TimestampValidator $timestamps = new TimestampValidator(), private CaptionValidator $captions = new CaptionValidator())
    {
    }

    /** @return list<Caption> */
    public function parse(string $content, bool $repair = false): array
    {
        $result = [];
        foreach (explode("\n", $content) as $offset => $rawLine) {
            $number = $offset + 1;
            $line = trim($rawLine);
            if ($line === '') {
                continue;
            }
            $parts = preg_split('/\s*\|\s*/u', $line, 3);
            if ($parts === false || count($parts) !== 3) {
                throw ConversionException::forLine($number, 'expected START | END | TEXT.');
            }
            [$start, $end, $text] = array_map('trim', $parts);
            if ($text === '') {
                throw ConversionException::forLine($number, 'caption text cannot be empty.');
            }
            $result[] = new Caption($this->timestamps->normalize($start, $number, 'start', $repair), $this->timestamps->normalize($end, $number, 'end', $repair), $text, $number);
        }
        if ($result === []) {
            throw new ConversionException('Input contains no subtitle entries.');
        }
        $this->captions->validate($result);
        return $result;
    }
}
