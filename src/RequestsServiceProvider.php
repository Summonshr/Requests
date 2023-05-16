<?php

namespace Summonshr\Requests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use ReflectionClass;
use Summonshr\Requests\Contracts\UniversalRequestInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RequestsServiceProvider extends ServiceProvider
{
    public $requests = [];

    public $rootNamespace = 'App\\Http\\Requests\\';

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
        
        if (!$class->implementsInterface(UniversalRequestInterface::class)) {
            // Won't register if it does not implement this interface
            return;
        }

        $action = str($class->getShortName())->kebab();
        $method = str($class->getShortName())->kebab()->explode('-')->first();
        $method = config('requests.default_method')($method);
        $action = str(str(str_replace($this->rootNamespace, '', $class->getNamespaceName()))->explode('\\')->join(''))->kebab() . '-' . $action;

        if ($class->getConstant('REQUEST_METHOD')) {
            $method = $class->getConstant('REQUEST_METHOD');
        }

        $method = strtoupper($method);

        if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            throw new \Exception('Invalid request method');
        }

        if ($class->getConstant('ACTION')) {
            $action = $class->getConstant('ACTION');
        }


        data_set($this->requests, $method . '.' . $action, $className);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->bind(UniversalRequestInterface::class, function (Application $app) {

            $request = data_get($this->requests, request()->method() . '.' . request('action'));

            if ($request === null) {
                throw new NotFoundHttpException();
            }

            return app($request);
        });

        if (config('requests.route_name')) {
            Route::any('/' . config('requests.route_name'), function (UniversalRequestInterface $request) {
                return $request->process();
            })->name(config('requests.route_name'));
        }

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
