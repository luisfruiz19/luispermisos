# LuisPermisos

LuisPermisos es una librería de Laravel que proporciona roles y permisos
e implementación con Gates y Policies.

## Requisitos

**El paquete de Laravel/ui debe estar instalado en Laravel para que funcione correctamente este paquete.**

## Instalación

Ejecute en el terminal el siguiente comando:

```bash
composer require luisrolespermisos/luispermisos
```

## Uso del paquete

Una vez instalado el paquete en laravel 7, es recomendable utilizar el siguiente comando para exportar las migraciones, archivo seeder, vistas, políticas y mucho más:

```bash
php artisan vendor:publish --provider="LuisRolesPermisos\LuisPermisos\LuisPermisosServiceProvider"

```

Luego de haber ejecutado el comando anterior, vamos a revisar en la instalación de laravel el siguiente archivo **config/LuisPermisos.php**

```php
return [
  'RouteRole' => 'role',
  'RouteUser' => 'user',
  'IdRoleDefault' => 2
];
```

En este, podremos cambiar las urls que vienen por defecto tanto para acceder a los roles como para los usuarios. Por otro lado, podremos también cambiar cual será el id del rol por defecto que se asignará cuando se registre un usuario.
Si luego de realizar los cambios en el archivo de configuración, no se reflejan, entonces debe ejecutar el siguiente comando en el terminal:

```bash
php artisan config:clear
```

Antes de ejecutar el comando

```bash
php artisan migrate
```

recomendamos realizar la siguiente configuración en el modelo User:

```php

use Illuminate\Foundation\Auth\User as Authenticatable;

//agregamos este trait
use LuisRolesPermisos\LuisPermisos\Traits\UserTrait;

class User extends Authenticatable
{
	//usamos el trait
    use UserTrait;

    // ...
}

```

Debemos revisar el archivo seed LuisPermissionInfoSeeder.php, exportado por el paquete en su instalación de laravel en la siguiente ruta: **database/seeds/LuisPermissionInfoSeeder.php**, ya que, en este, encontrarás lo siguiente:

- La creación del usuario admin, con el correo admin@admin.com. El usuario es **admin** y la contraseña es: **admin**.
- Creación de dos roles: Rol Admin y Rol Autenticated User.
- Creación de la relación del rol Admin y el usuario admin.
- Creación de los permisos por defecto.

Un ejemplo de lo que encontrarás en el archivo antes mencionado para la creación de permisos es la siguiente:

```php

		//permission role
        $permission = Permission::create([
            'name' => 'List role',
            'slug' => 'role.index',
            'description' => 'A user can list role',
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Show role',
            'slug' => 'role.show',
            'description' => 'A user can see role',
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Create role',
            'slug' => 'role.create',
            'description' => 'A user can create role',
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Edit role',
            'slug' => 'role.edit',
            'description' => 'A user can edit role',
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Destroy role',
            'slug' => 'role.destroy',
            'description' => 'A user can destroy role',
        ]);

        $permission_all[] = $permission->id;

```

Recomendamos crear los permisos adicionales que necesitas al final de este archivo con la misma estructura que la anterior.

Una vez tengas todos los permisos que necesitas, debes modificar el archivo **DatabaseSeeder.php** para cargar el seeder.

Adjunto un ejemplo de como debe quedar este archivo:

```php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        $this->call(LuisPermissionInfoSeeder::class);
    }
}


```

Ahora si podremos ejecutar el siguiente comando en el terminal

```bash
php artisan migrate --seed

```

Una vez cargadas todas las tablas a tu base de datos con todos los permisos de lugar, ya podrás acceder a la url /role para acceder a los roles y /user para los usuarios.

## Blindar los controladores con Gates y Políticas:

## Gates:

Supongamos que tenemos:

- Permisos para los roles en el archivo LuisPermissionInfoSeeder
- Un modelo llamado: Role
- Un controlador llamado: RoleController
- Un archivo blade ubicado en views/role/index.blade.php

Y supongamos que quieres validar si un usuario tiene el siguiente permiso:

```php
$permission = Permission::create([
   'name' => 'Create role',
   'slug' => 'role.create',
   'description' => 'A user can create role',
]);
```

Para blindar (comprobar si tiene o no acceso un usuario a un método) con Gates cada método del controlador podemos hacerlo de dos formas:

1. Primera forma usando Gates:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LuisPermission\Models\Role;
use App\LuisPermission\Models\Permission;
//Añadimos el facades Gate
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    ...
    public function create()
    {
        //Con gate revisamos si el usuario actual tiene acceso al permiso que tiene el slug: role.create
        Gate::authorize('haveaccess','role.create');

        $permissions = Permission::get();

        return view('role.create', compact('permissions'));
    }
    ...
}

```

2. Segunda Forma usando \$this:

```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LuisPermission\Models\Role;
use App\LuisPermission\Models\Permission;

class RoleController extends Controller
{
    ...
    public function create()
    {
        //con $this revisamos si el usuario actual tiene acceso al permiso que tiene el slug: role.create
        $this->authorize('haveaccess','role.create');

        $permissions = Permission::get();

        return view('role.create', compact('permissions'));
    }
    ...
}

```

## Políticas:

Supongamos que quieres realizar una comprobación para que un usuario pueda ver los registros que ha el ha creado. Te explicaremos como hacerlo con un ejemplo puntual que este paquete trae.

1. En el archivo seed: **LuisPermissionInfoSeeder.php** debemos agregar los permisos que queramos, y deben contemplar esta misma estructura, pero realmente lo mas importante es el slug de los permisos, porque es con este campo que vamos a hacer la validación si el usuario puede o no hacer una acción en el sistema, adjunto los permisos que aplican para este escenario.

```php
$permission = Permission::create([
    'name' => 'Show own user',
    'slug' => 'userown.show',
    'description' => 'A user can see own user',
]);

$permission_all[] = $permission->id;

$permission = Permission::create([
    'name' => 'Edit own user',
    'slug' => 'userown.edit',
    'description' => 'A user can edit own user',
]);
```

2. Adjunto una muestra de como debe de realizarse la validación de la política en el controlador del usuario o en el que entendamos. En nuestro caso en el UserController, pondremos los métodos que aplica para los permisos puestos en la sección anterior:

   // userown.edit = editar su propio usuario.

   // userown.show = ver su propio usuario

```php
    public function show(User $user)
    {
        /*
        Aquí estamos trabajando con las políticas y por ende, estamos
        realizando dos validaciones: 1, con el user.show (En este lo que
        logramos es que si tienen el acceso global user.show puede ver
        todos los usuarios incluyendo su propio usuario) y 2, userown.show
        (En el cual vamos a validar si no tiene el user.show, va a revisar
        si tiene como segundo permiso el userown.show y si lo tiene, el
        podrá ver su propio usuario, de lo contrario, le mostrará acceso denegado).
        */
        $this->authorize('view', [$user, ['user.show','userown.show'] ]);

        $roles= Role::orderBy('name')->get();

        //return $roles;

        return view('user.view', compact('roles', 'user'));
    }


    ...

    public function edit(User $user)
    {

        /*
         Aquí estamos trabajando con las políticas y por ende, estamos
         realizando dos validaciones: 1, con el user.edit (En este lo que
         logramos es que si tienen el acceso global user.edit puede editar
         todos los usuarios incluyendo su propio usuario) y 2, userown.edit
         (En el cual vamos a validar si no tiene el user.edit, va a revisar
         si tiene como segundo permiso el userown.edit y si lo tiene, el
         podrá editar su propio usuario, de lo contrario, le mostrará
         acceso denegado).
        */
        $this->authorize('update', [$user, ['user.edit','userown.edit'] ]);

        $roles= Role::orderBy('name')->get();

        //return $roles;

        return view('user.edit', compact('roles', 'user'));
    }

```

3. Para que lo anterior funcione correctamente, debemos de hacer algunos ajustes en el archivo **app/Providers/AuthServiceProvider.php** en nuestro caso vamos a observar este archivo ubicado en **src/AuthServiceProvider.php**.

En el ejemplo que tiene nuestro módulo, nosotros queremos implementar una politica al modelo User, y por ende, ustedes observarán que estamos usando _App\User_ y _App\Policies\UserPolicy_.

```php

namespace LuisRolesPermisos\LuisPermisos;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User; //debemos agregar el modelo que vamos a usar
use App\Policies\UserPolicy; //debemos agregar una política

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         /*
            Debemos decirle a laravel que la política:
            UserPolicy se va a aplicar al modelo User.
         */
         User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        /*Desde aquí*/
        $this->registerPolicies();

        Gate::define('haveaccess', function (User $user, $perm){
            //dd($perm);
            return $user->havePermission($perm);
            //return $perm;
        });

        /*Hasta aquí*/

    }
}

```

En el método boot del archivo anterior, está el corazón del paquete y por ende, es necesiario que este código esté unicamente en este archivo y no en los archivos de Laravel.

Lo que hace este paquete es recibir dos parámetros uno para el usuario que está logueado actualmente y el permiso que se necesita.

4. Vamos a verificar el archivo UserPolicy:

```php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    ...
    public function update(User $usera, User $user, $perm=null)
    {
        if ($usera->havePermission($perm[0])){
            return true;
        }else
        if ($usera->havePermission($perm[1])){
            return $usera->id === $user->id;
        }
        else {
            return false;
        }
    }


    ...

    public function view(User $usera, User $user, $perm=null)
    {
        if ($usera->havePermission($perm[0])){
            return true;
        }else
        if ($usera->havePermission($perm[1])){
            return $usera->id === $user->id;
        }
        else {
            return false;
        }


    }
}



```

En el archivo anterior, encontraremos la forma correcta de como trabajar las políticas en este paquete, y lo que hace es lo siguiente:

- Recibe 3 parametros:
  - El usuario actual que está logueado,
  - El usuario que le estamos pasando en el cual queremos realizar la validación para saber si puede o no tener el acceso
  - Los permisos de la sección 1 de las políticas.

Observemos detenidamente lo que hace el siguiente código:

```php
/*confirmamos si tiene el acceso global como por ejemplo user.edit y si lo tiene retorno true para que me permita el acceso
*/
if ($usera->havePermission($perm[0])){
  return true;
}
/* de lo contrario, si tiene el acceso userown.edit, el cual es usuado
para saber si el usuario puede editar su propio registro, entonces
hacemos una validación para saber si el usuario que está logueado
puede está accediendo a su propio registro. Si es igual el id del usuario
entonces retornará true y lo dejará pasar, si no es igual
retornará false.
*/
else if ($usera->havePermission($perm[1])){
  return $usera->id === $user->id;
}
else {
  return false;
}

```

## Blindar en archivos Blade.

Supongamos que tenemos el siguiente permiso:

```php
$permission = Permission::create([
   'name' => 'Create role',
   'slug' => 'role.create',
   'description' => 'A user can create role',
]);
```

En blade nosotros podemos utilizar las directivas ** @can** y **@endcan** para esto. Adjunto un ejemplo de su uso:

```html
@can('haveaccess','role.create')
<a href="{{route('role.create')}}" class="btn btn-primary float-right"
  >Create
</a>
@endcan
```

Como podemos observar, solo tenemos que escribir dos parametros:

- siempre debemos usar: _haveaccess_ como primer parametro.
- el slug del permiso que queremos validar y si tiene acceso, lo mostrará y si no tiene acceso no lo mostrará.

## Contribuciones

Dios les bendiga.

## Licencia

[MIT](./LICENSE.md)
