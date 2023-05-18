<?php

namespace Matthewbdaly\LaravelGridFSStorage;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\GridFS\GridFSAdapter;

/**
 * Service provider for GridFS Storage
 */
class GridFSStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('gridfs', function ($app, $config) {
            $mongoClient = new MongoClient();
            $gridFs = $mongoClient->selectDB($config['name'])->getGridFS();

            $adapter = new GridFSAdapter($gridFs);
            return new Filesystem($adapter);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
