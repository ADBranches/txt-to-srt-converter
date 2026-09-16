<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Feature;

use NoviqLabs\TxtToSrt\Http\CsrfTokenManager;
use PHPUnit\Framework\TestCase;

final class CsrfProtectionTest extends TestCase
{
    public function testMissingAndIncorrectTokensAreRejected(): void
    {
        $_SESSION = [];
        $manager = new CsrfTokenManager();
        $manager->token();
        self::assertFalse($manager->verify(null));
        self::assertFalse($manager->verify('incorrect'));
    }
}
