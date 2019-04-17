<?php
declare(strict_types=1);

namespace Radarlog\S3Uploader\Tests;

use Symfony\Bundle\FrameworkBundle\Client;

class ControllerTestCase extends FunctionalTestCase
{
    /** @var Client */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::$container->get('test.client');
        $this->client->disableReboot();
    }
}
