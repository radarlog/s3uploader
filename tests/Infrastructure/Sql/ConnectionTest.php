<?php

declare(strict_types=1);

namespace Radarlog\Doop\Tests\Infrastructure\Sql;

use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Radarlog\Doop\Infrastructure\Sql\Connection;
use Radarlog\Doop\Tests\FunctionalTestCase;

class ConnectionTest extends FunctionalTestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Connection $connection */
        $connection = self::$container->get(Connection::class);
        $this->connection = $connection;
    }

    /**
     * @psalm-suppress RedundantCondition
     */
    public function testIsMasterReplica(): void
    {
        self::assertInstanceOf(PrimaryReadReplicaConnection::class, $this->connection);
    }

    public function testImportResultsTable(): void
    {
        self::assertSame('"images"', $this->connection->imagesTable());
    }
}
