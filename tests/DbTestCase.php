<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests;

use Radarlog\S3Uploader\Tests\Infrastructure\MySql\InMemoryDb;

class DbTestCase extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        self::$container->get(InMemoryDb::class)->reset();

        parent::tearDown();
    }
}
