<?php

declare(strict_types=1);
namespace NoviqLabs\TxtToSrt\Tests\Unit;
use NoviqLabs\TxtToSrt\Exception\ConversionException;
use NoviqLabs\TxtToSrt\Validation\SrtValidator;
use PHPUnit\Framework\TestCase;
final class SrtValidatorTest extends TestCase {
 public function testAcceptsValidSrt(): void { (new SrtValidator())->validate("1\n00:00:01,000 --> 00:00:02,000\nText\n", 1); self::addToAssertionCount(1); }
 public function testRejectsInvalidIndex(): void { $this->expectException(ConversionException::class); (new SrtValidator())->validate("2\n00:00:01,000 --> 00:00:02,000\nText\n", 1); }
}
