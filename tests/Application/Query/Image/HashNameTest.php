<?php

declare(strict_types=1);

namespace Radarlog\Doop\Tests\Application\Query\Image;

use Radarlog\Doop\Application\Query\Image\HashName;
use Radarlog\Doop\Domain\Image\Hash;
use Radarlog\Doop\Tests\UnitTestCase;

class HashNameTest extends UnitTestCase
{
    private HashName $queryDto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryDto = new HashName('f32b67c7e26342af42efabc674d441dca0a281c5', 'name');
    }

    public function testHash(): void
    {
        $hash = new Hash('f32b67c7e26342af42efabc674d441dca0a281c5');

        self::assertEquals($hash, $this->queryDto->hash());
    }

    public function testName(): void
    {
        self::assertSame('name', (string) $this->queryDto->name());
    }
}
