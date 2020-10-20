<?php

use Illuminate\Support\Facades\Route;




Route::resource(config('LuisPermisos.RouteRole'), 'LuisRolesPermisos\LuisPermisos\Http\Controllers\RoleController')->names('role')->middleware(['web']);

Route::resource(config('LuisPermisos.RouteUser'), 'LuisRolesPermisos\LuisPermisos\Http\Controllers\UserController', ['except' => ['create', 'store']])->names('user')->middleware(['web']);
