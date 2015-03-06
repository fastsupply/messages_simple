<?php namespace App\Providers;

use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;

class AnnotationsServiceProvider extends ServiceProvider {

	public $scanWhenLocal = true;

    public $scanRoutes = [
        'App\Http\Controllers\ConversationController',
        'App\Http\Controllers\MessageController',
        'App\Http\Controllers\LoginController',
    ];

}
