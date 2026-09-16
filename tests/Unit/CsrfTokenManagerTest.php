<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use PHPUnit\Framework\TestCase;

final class CsrfTokenManagerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testCreatesStableTokenAndRejectsInvalidCandidate(): void
    {
        $manager = new CsrfTokenManager(32);
        $token = $manager->token();
        self::assertSame(64, strlen($token));
        self::assertSame($token, $manager->token());
        self::assertTrue($manager->verify($token));
        self::assertFalse($manager->verify('invalid'));
    }
}
