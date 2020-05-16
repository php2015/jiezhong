<?php

namespace AetherUpload;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use AetherUpload\Console\BuildRedisHashesCommand;
use AetherUpload\Console\CleanUpDirectoryCommand;
use AetherUpload\Console\ListGroupDirectoryCommand;
use AetherUpload\Console\PublishCommand;
use League\Flysystem\Filesystem;

class AetherUploadServiceProvider extends ServiceProvider
{

    protected $defer = false;

    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../views', 'upload');

        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'upload');

        $this->publishes([
            __DIR__ . '/../config/aetherupload.php'         => config_path('upload.php'),
            __DIR__ . '/../assets/aetherupload.js'          => public_path('vendor/upload/js/upload.js'),
            __DIR__ . '/../assets/spark-md5.min.js'         => public_path('vendor/upload/js/spark-md5.min.js'),
            __DIR__ . '/../uploads/aetherupload_file'       => storage_path('app/upload/file'),
            __DIR__ . '/../uploads/aetherupload_header'     => storage_path('app/upload/_header'),
            __DIR__ . '/../translations/zh/messages.php'    => base_path('resources/lang/vendor/upload/zh/messages.php'),
            __DIR__ . '/../translations/en/messages.php'    => base_path('resources/lang/vendor/upload/en/messages.php'),
            __DIR__ . '/../middleware/AetherUploadCORS.php' => app_path('Http/Middleware/AetherUploadCORS.php'),
        ], 'upload');

        if ( ! $this->app->routesAreCached() ) {
            require __DIR__ . '/../routes/routes.php';
        }

        Storage::extend('redis', function ($app, $config) {
            return new Filesystem(new RedisAdapter(new RedisClient()), $config);
        });

        if ( $this->app->runningInConsole() ) {
            $commands = [PublishCommand::class];
            if ( Util::isStorageHost() ) {
                array_push($commands, BuildRedisHashesCommand::class, CleanUpDirectoryCommand::class, ListGroupDirectoryCommand::class);
            }
            $this->commands($commands);
        }
    }

    public function register()
    {
        //
    }


}
