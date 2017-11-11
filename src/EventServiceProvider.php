<?php

namespace Mods\Scss;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'theme.asset.deploy.after' => [
            CombineTheme::class
        ]
    ];

}