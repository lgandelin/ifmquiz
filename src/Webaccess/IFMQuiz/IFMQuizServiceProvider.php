<?php

namespace Webaccess\IFMQuiz;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Webaccess\IFMQuiz\Commands\MarkQuizCommand;

class IFMQuizServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(Router $router)
    {
        setlocale(LC_TIME, 'fr_FR.utf8');

        //Patterns
        $basePath = __DIR__.'/../../';

        //$router->aliasMiddleware('user', UserMiddleware::class);

        $this->loadRoutesFrom($basePath . 'routes/web.php');

        $this->loadViewsFrom($basePath.'resources/views/', 'ifmquiz');
        $this->loadTranslationsFrom($basePath.'resources/lang/', 'ifmquiz');

        //Assets publications
        $this->publishes([
            $basePath.'resources/assets/css' => base_path('public/css'),
            $basePath.'resources/assets/js' => base_path('public/js'),
            $basePath.'resources/assets/fonts' => base_path('public/fonts'),
            $basePath.'resources/assets/img' => base_path('public/img'),
        ], 'assets');

        $this->publishes([
            $basePath.'database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    public function register()
    {
        /*App::bind('GetClientsInteractor', function () {
             return new GetClientsInteractor(
                 new EloquentClientRepository()
             );
         });*/

        App::register('Intervention\Image\ImageServiceProvider');

        $this->commands([
            MarkQuizCommand::class,
        ]);
    }
}
