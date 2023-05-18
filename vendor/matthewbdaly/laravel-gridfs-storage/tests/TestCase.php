<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	protected function getPackageProviders($app)
	{
		return ['Matthewbdaly\LaravelGridFSStorage\GridFSStorageServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('filesystems.default', 'gridfs');
        $app['config']->set('filesystems.cloud', 'gridfs');
        $app['config']->set('filesystems.disks.azure', [
            'driver'    => 'gridfs',
            'name'      => 'MY_GRIDFS_DATABASE_NAME',
        ]);
    }
}
