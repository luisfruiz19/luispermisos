<?php


use Illuminate\Database\Seeder;
use App\User;
use LuisRolesPermisos\LuisPermisos\Models\Role;
use LuisRolesPermisos\LuisPermisos\Models\Permission;
use Illuminate\Support\Facades\DB;

class LuisPermissionInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Vacea las tablas desde 0
        DB::statement("SET foreign_key_checks=0");
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();
        Permission::truncate();
        Role::truncate();
        DB::statement("SET foreign_key_checks=1");

        //Valida si existe el email admin
        $userAdmin = User::where('email', 'admin@admin.com')->first();
        if ($userAdmin) {
            $userAdmin->delete();
        }
        //Crea el usuario admin
        $userAdmin = User::create([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('admin')
        ]);
        //Crea el Rol Admin
        $roleAdmin = Role::create([
            'name'          => 'Admin',
            'slug'          => 'admin',
            'description'   => 'Administrator',
            'full-access'   => 'yes'
        ]);
        //Sincroniza la tabla pivot y los crea
        $userAdmin->roles()->sync([$roleAdmin->id]);

        //Crea el Rol Admin
        $roleUser = Role::create([
            'name'          => 'Registered User',
            'slug'          => 'registereduser',
            'description'   => 'Registered User',
            'full-access'   => 'no'
        ]);

        //Permisos Role
        $permisson_all = [];

        $permission = Permission::create([
            'name' => 'List role',
            'slug' => 'role.index',
            'description' => 'A user can list role'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Show role',
            'slug' => 'role.show',
            'description' => 'A user can see role'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Create role',
            'slug' => 'role.create',
            'description' => 'A user can create role'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Edit role',
            'slug' => 'role.edit',
            'description' => 'A user can edit role'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Destroy role',
            'slug' => 'role.destroy',
            'description' => 'A user can destroy role'
        ]);

        $permisson_all[] = $permission->id;

        //  $roleAdmin->permissions()->sync($permisson_all);


        //Permisos Usuario

        $permission = Permission::create([
            'name' => 'List user',
            'slug' => 'user.index',
            'description' => 'A user can list user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Show user',
            'slug' => 'user.show',
            'description' => 'A user can see user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Create user',
            'slug' => 'user.create',
            'description' => 'A user can create user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Edit user',
            'slug' => 'user.edit',
            'description' => 'A user can edit user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Destroy user',
            'slug' => 'user.destroy',
            'description' => 'A user can destroy user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Show own user',
            'slug' => 'userown.show',
            'description' => 'A user can see own user'
        ]);

        $permisson_all[] = $permission->id;

        $permission = Permission::create([
            'name' => 'Edit own user',
            'slug' => 'userown.edit',
            'description' => 'A user can edit own user'
        ]);

        $permisson_all[] = $permission->id;

        //   $roleAdmin->permissions()->sync($permisson_all);
    }
}
