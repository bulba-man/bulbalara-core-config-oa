<?php

namespace Bulbalara\CoreConfigOa;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Extension;
use Illuminate\Support\Facades\Schema;

class CoreConfigExtension extends Extension
{
    const PACKAGE_NAME = "config-oa";

    /**
     * Load configure into laravel from database.
     *
     * @return void
     */
    public static function load()
    {
        try {
            if (!Schema::hasTable(config('bl.config.db.table', 'core_config'))) {
                return;
            }
        } catch (\Exception $e) {
            return;
        }

        foreach (\Bulbalara\CoreConfig\Models\Config::all() as $config) {
            $value = (!is_null($config->value)) ? $config->value : $config->default;
            config([$config->path => $value]);
        }
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend(self::PACKAGE_NAME, __CLASS__);
    }

    /**
     * Register routes for open-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource(
                config('bl.config.urls.settings_uri') . '/' . config('bl.config.urls.settings_config_uri'),
                config('bl.config.controllers.config_controller')
            );
        });
    }

    public static function import()
    {
        $settingsUri = config('bl.config.urls.settings_uri', 'settings');
        $configUri = $settingsUri . '/' . config('bl.config.urls.settings_config_uri', 'config');

        $settingsMenu = parent::createMenu(__('bl.config::core_config.menu_main_label'), $settingsUri, 'icon-toggle-on', 0, [], 'settings');
        parent::createMenu(__('bl.config::core_config.menu_config_label'), $configUri, 'icon-cogs', $settingsMenu->getKey(), [], 'config');

        parent::createPermission(__('bl.config::core_config.role_main_label'), 'ext.settings', $settingsUri.'*');
        parent::createPermission(__('bl.config::core_config.role_config_label'), 'ext.settings.config', $configUri.'*');
    }
}
