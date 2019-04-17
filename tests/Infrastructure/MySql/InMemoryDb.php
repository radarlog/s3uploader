<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests\Infrastructure\MySql;

use Radarlog\S3Uploader\Application\Query;
use Radarlog\S3Uploader\Domain;

final class InMemoryDb implements Domain\Repository, Query\Image\FindAll, Query\Image\FindOne
{
    /** @var array */
    private $rows = [];

    /**
     * @inheritDoc
     */
    public function add(Domain\Aggregate $image): void
    {
        $this->rows[$image->id()->toString()] = $image->getState()->asArray();

        uasort($this->rows, static function (array $state1, array $state2) {
            return $state2['uploaded_at'] <=> $state1['uploaded_at'];
        });
    }

    /**
     * @inheritDoc
     */
    public function getById(Domain\Identity $id): ?Domain\Aggregate
    {
        $uuid = $id->toString();

        if (!array_key_exists($uuid, $this->rows)) {
            return null;
        }

        $state = new Domain\Image\State($this->rows[$uuid]);

        return Domain\Image::fromState($state);
    }

    /**
     * @inheritDoc
     */
    public function sortedByUploadDate(): array
    {
        return array_map(static function (array $state) {
            return new Query\Image\UuidNameDate($state['uuid'], $state['name'], $state['uploaded_at']);
        }, $this->rows, array_keys($this->rows));
    }

    /**
     * @inheritDoc
     */
    public function hashNameByUuid(string $uuid): ?Query\Image\HashName
    {
        return array_key_exists($uuid, $this->rows)
            ? new Query\Image\HashName($this->rows[$uuid]['hash'], $this->rows[$uuid]['name'])
            : null;
    }
}
