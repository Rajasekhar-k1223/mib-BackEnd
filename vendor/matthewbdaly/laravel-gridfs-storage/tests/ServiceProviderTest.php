<?php

namespace Tests;

use Storage;
use Mockery as m;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_sets_up_the_storage_correctly()
    {
        $storage = $this->app['filesystem'];
        $this->assertEquals('gridfs', $storage->getDefaultDriver());
        $this->assertEquals('gridfs', $storage->getDefaultCloudDriver());
    }
}
