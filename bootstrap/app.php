<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use MoonShine\Laravel\Http\Middleware\Authenticate;
use MoonShine\Laravel\Http\Middleware\ChangeLocale;

return $app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        // Регистрация кастомных middleware для MoonShine
        $middleware->alias([
          'moonshine' => ChangeLocale::class, // Middleware MoonShine для локализации
          'auth' => Authenticate::class,      // Кастомный middleware MoonShine для аутентификации
        ]);
        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Опционально: добавьте MoonShine middleware в глобальный стек, если требуется
//        $middleware->global(append: [
//          ChangeLocale::class,
//        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Устанавливаем путь к public директории из .env
//$publicPath = env('PUBLIC_PATH', 'public');
//if ($publicPath !== 'public') {
//    $app->usePublicPath(realpath($app->basePath($publicPath)));
//}
//
//return $app;