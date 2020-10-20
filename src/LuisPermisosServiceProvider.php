<?php


namespace LuisRolesPermisos\LuisPermisos;

use Illuminate\Support\ServiceProvider;

class LuisPermisosServiceProvider extends ServiceProvider
{

    public function register()
    {
        //En caso de que no funcione ejecutar el siguiente comando en laravel : -- php artisan config:clear
        $this->mergeConfigFrom(
            __DIR__ . '/../config/LuisPermisos.php',
            'LuisPermisos'
        );
    }

    public function boot()
    {
        //load data for migrations
        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations'
        ]);

        //publicar migraciones
        $this->publishes([
            __DIR__ . '/../database/migrations' =>  database_path('migrations')
        ], 'LuisPermisos-migrations');

        //publicar seeds
        $this->publishes([
            __DIR__ . '/../database/seeds' =>  database_path('seeds')
        ], 'LuisPermisos-seeds');

        //publicar policies y gates
        $this->publishes([
            __DIR__ . '/../Policies' =>  app_path('Policies')
        ], 'LuisPermisos-policies');

        //publicar policies y gates
        $this->loadRoutesFrom(
            __DIR__ . '/../routes/web.php'
        );

        //publicar vistas
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'LuisPermisos'
        );

        //publicar vistas
        $this->publishes([
            __DIR__ . '/../resources/views' =>  resource_path('views/vendor/LuisPermisos')
        ], 'LuisPermisos-views');

        //publicar configuracion
        $this->publishes([
            __DIR__ . '/../config/LuisPermisos.php' =>  config_path('LuisPermisos.php')
        ], 'LuisPermisos-config');
    }
}
