<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests\Infrastructure\Cli;

use Radarlog\S3Uploader\Domain\Image;
use Radarlog\S3Uploader\Domain\Repository;
use Radarlog\S3Uploader\Tests\FunctionalTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ImagesListTest extends FunctionalTestCase
{
    /** @var Repository */
    private $repository;

    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = self::$container->get('test.repository');

        $application = new Application(self::$kernel);
        $command = $application->find('images:list');

        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithNoImages(): void
    {
        $this->commandTester->execute([]);

        self::assertStringContainsString('No images uploaded', $this->commandTester->getDisplay());
    }

    public function testExecuteWithUploadedImages(): void
    {
        $state = new Image\State([
            'uuid' => '572b3706-ffb8-423c-a317-d0ca8016a345',
            'hash' => 'f32b67c7e26342af42efabc674d441dca0a281c5',
            'name' => 'unique_name',
            'uploaded_at' => '2018-03-18 23:22:36',
        ]);
        $image = Image::fromState($state);
        $this->repository->add($image);

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();

        self::assertStringContainsString('Images (1)', $output);

        self::assertStringContainsString('UUID', $output);
        self::assertStringContainsString('Name', $output);
        self::assertStringContainsString('Uploaded Date', $output);

        self::assertStringContainsString('572b3706-ffb8-423c-a317-d0ca8016a345', $output);
        self::assertStringContainsString('unique_name', $output);
        self::assertStringContainsString('2018-03-18 23:22:36', $output);
    }
}
