<?php

namespace App\Providers;

use App\Exceptions\ApiHandler;
use App\Http\Responses\ApiResponder;
use App\Http\Responses\ResponsesInterface;
use App\Search\ElasticRepository;
use App\Search\EloquentRepository;
use App\Search\FeedbackRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Use the ApiResponder as the concrete implementation for the ResponsesInterface
        $this->app->bind(ResponsesInterface::class, ApiResponder::class);

        // Use the ApiHandler as the main exception handler
        $this->app->singleton(ExceptionHandler::class, ApiHandler::class);

        // bind search repository to it's implementation.
        $this->app->bind(FeedbackRepository::class, function ($app) {
            if (config('services.search.enabled')) {
                return new ElasticRepository($app->make(Client::class));
            }
            return new EloquentRepository;
        });

        // bind client to it's builder using elasticsearch current configuration.
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
