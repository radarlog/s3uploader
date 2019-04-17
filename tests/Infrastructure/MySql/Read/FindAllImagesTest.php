<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests\Infrastructure\MySql\Read;

use Radarlog\S3Uploader\Application\Query;
use Radarlog\S3Uploader\Domain\Image;
use Radarlog\S3Uploader\Domain\Repository;
use Radarlog\S3Uploader\Tests\FunctionalTestCase;

class FindAllImagesTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /** @var Repository $repository */
        $repository = self::$container->get('test.repository');

        $state1 = new Image\State([
            'uuid' => '9f2149bb-b6e5-4ae0-a188-e616cddc8e98',
            'hash' => '2346ad27d7568ba9896f1b7da6b5991251debdf2',
            'name' => 'name1',
            'uploaded_at' => '2018-01-01 23:22:36',
        ]);
        $image1 = Image::fromState($state1);
        $repository->add($image1);

        $state2 = new Image\State([
            'uuid' => '572b3706-ffb8-423c-a317-d0ca8016a345',
            'hash' => 'f32b67c7e26342af42efabc674d441dca0a281c5',
            'name' => 'name2',
            'uploaded_at' => '2018-03-18 23:22:36',
        ]);
        $image2 = Image::fromState($state2);
        $repository->add($image2);
    }

    public function testSortedByUploadDate(): void
    {
        /** @var Query\Image\FindAll $query */
        $query = self::$container->get('test.query.all');

        $result = $query->sortedByUploadDate();

        $expected = [
            new Query\Image\UuidNameDate('572b3706-ffb8-423c-a317-d0ca8016a345', 'name2', '2018-03-18 23:22:36'),
            new Query\Image\UuidNameDate('9f2149bb-b6e5-4ae0-a188-e616cddc8e98', 'name1', '2018-01-01 23:22:36'),
        ];

        self::assertCount(2, $result);
        self::assertEquals($expected, $result);
    }
}
