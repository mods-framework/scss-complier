<?php

return [
    'mod_scss' => [
        'name' => 'Scss Complier',
        'type' => 'module',
        'providers' => [
            \Mods\Scss\EventServiceProvider::class
        ],
        'aliases' => [
        ],
        'depends' => [
            'mod_theme'
        ],
        'autoload' => [
        ]
    ]
];
