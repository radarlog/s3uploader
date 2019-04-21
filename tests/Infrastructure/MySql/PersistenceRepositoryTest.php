<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests\Infrastructure\MySql;

use Radarlog\S3Uploader\Domain\Image;
use Radarlog\S3Uploader\Domain\Repository;
use Radarlog\S3Uploader\Infrastructure\MySql\Connection;
use Radarlog\S3Uploader\Tests\FunctionalTestCase;

class PersistenceRepositoryTest extends FunctionalTestCase
{
    /** @var Repository */
    private $repository;

    /** @var Connection */
    private $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = self::$container->get('test.repository.mysql');

        $this->connection = self::$container->get(Connection::class);

        $this->connection->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->connection->rollBack();

        parent::tearDown();
    }

    public function testAddNew(): void
    {
        $name = new Image\Name('name');
        $hash = new Image\Hash('f32b67c7e26342af42efabc674d441dca0a281c5');

        $image1 = new Image($hash, $name);
        $this->repository->add($image1);

        $id = $image1->id();

        $image2 = $this->repository->getById($id);

        self::assertSame($id->toString(), $image2->id()->toString());
    }

    public function testGetByNonExistingId(): void
    {
        $identity = new Image\Identity('572b3706-ffb8-423c-a317-d0ca8016a345');

        self::assertNull($this->repository->getById($identity));
    }
}
