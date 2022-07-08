<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();
$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
*/

$app->configure('app');
$app->configure('database');
$app->configure('cors');
$app->configure('permission');
$app->configure('auth');
$app->configure('jwt');
$app->configure('logging');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
*/
$app->middleware([
    Fruitcake\Cors\HandleCors::class,
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'permission' => Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role'=> Spatie\Permission\Middlewares\RoleMiddleware::class,
    'h2hiak'=> App\Http\Middleware\H2HIAKJWTCheckMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Spatie\Permission\PermissionServiceProvider::class);
$app->register(PHPOpenSourceSaver\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Fruitcake\Cors\CorsServiceProvider::class);
$app->register(PHPOpenSourceSaver\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);

/*
|--------------------------------------------------------------------------
| alias
|--------------------------------------------------------------------------
*/
$app->alias('cache', \Illuminate\Cache\CacheManager::class);
$app->alias('Helper', \App\Helpers\Helper::class);
$app->alias('HelperDMaster', \App\Helpers\HelperDMaster::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

return $app;
