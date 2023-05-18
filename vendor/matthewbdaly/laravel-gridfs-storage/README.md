# laravel-gridfs-storage
MongoDB GridFS integration for Laravel's Storage API

# Installation

Install the package using composer:

```bash
composer require matthewbdaly/laravel-gridfs-storage
```

On Laravel versions before 5.5 you also need to add the service provider to `config/app.php` manually:

```php
    Matthewbdaly\LaravelGridFSStorage\GridFSStorageServiceProvider::class,
```

Then add this to the `disks` section of `config/filesystems.php`:

```php
        'gridfs' => [
            'driver'    => 'gridfs',
            'name'      => env('GRIDFS_STORAGE_NAME'),
        ],
```

Finally, add the field `GRIDFS_STORAGE_NAME` to your `.env` file with the appropriate credentials. Then you can set the `gridfs` driver as either your default or cloud driver and use it to fetch and retrieve files as usual.
