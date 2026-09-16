<?php

declare(strict_types=1);

namespace NoviqLabs\TxtToSrt\Tests\Unit;

use NoviqLabs\TxtToSrt\Support\TemporaryFileManager;
use PHPUnit\Framework\TestCase;

final class TemporaryFileManagerTest extends TestCase
{
    public function testCleanupRemovesManagedFiles(): void
    {
        $manager = new TemporaryFileManager();
        $file = $manager->create('phase7-');
        self::assertFileExists($file);
        $manager->cleanup();
        self::assertFileDoesNotExist($file);
    }
}
