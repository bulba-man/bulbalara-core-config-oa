<?php

return [
    'database' => [
        'connection' => '',
        'oa_table' => 'core_config_admin',
    ],
    'urls' => [
        'settings_uri' => 'settings',
        'settings_config_uri' => 'config',
    ],
    'controllers' => [
        'config_controller' => \Bulbalara\CoreConfigOa\ConfigController::class
    ],
];
