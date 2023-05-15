<?php

namespace Summonshr\Requests;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use ReflectionClass;
use Summonshr\Requests\Controllers\UniversalController;

class RequestsServiceProvider extends ServiceProvider
{

    public $requests = [];

    protected function registerRoutes(): void
    {
        $directories = Arr::wrap(app_path() . '/Http/Requests');
        $files = (new Finder())->files()->name('*.php')->in($directories)->sortByName();

        collect($files)->each(fn (SplFileInfo $file) => $this->registerFile($file));
    }

    protected function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst(app()->path(), '', $file->getRealPath()), DIRECTORY_SEPARATOR);
        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return 'App\\' . $class;
    }

    protected function registerFile($file)
    {

        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($file);

        $this->processAttributes($fullyQualifiedClassName);
    }

    protected function processAttributes(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        if (!$class->hasConstant('REQUEST_METHOD') || !$class->hasConstant('ACTION')) {
            return;
        }

        abort_unless($class->hasMethod('process'), 500, 'Missing process method');

        data_set($this->requests, $class->getConstant('REQUEST_METHOD') . '.' . $class->getConstant('ACTION'), $className);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->bind(Request::class, function (Application $app) {
            $request = data_get($this->requests, request()->method() . '.' . request('action'));
            return app($request);
        });

        Route::any('/' . config('requests.route_name'), UniversalController::class);

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('requests.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'requests');
    }
}
