<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'supreme' => ['name' => 'Supreme'],
            'admin'   => ['name' => 'Admin'],
            'user'    => ['name' => 'User'],
        ];

        $permissions = [
            ['name' => 'create_user'],
            ['name' => 'edit_user'],
            ['name' => 'delete_user'],
            ['name' => 'create_product'],
            ['name' => 'edit_product'],
            ['name' => 'delete_product'],
            ['name' => 'create_category'],
            ['name' => 'edit_category'],
            ['name' => 'delete_category'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        foreach ($permissions as $permission) {
            Permission::create($permission)->syncRoles($roles['admin']);
        }


        (\App\Models\User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'supreme@dev.com',
            'password' => bcrypt('password'),
        ]))->assignRole($roles['supreme']);

        (\App\Models\User::factory()->create([
            'name'     => 'Administrator',
            'email'    => 'admin@dev.com',
            'password' => bcrypt('password'),
        ]))->syncRoles($roles['admin']);

        (\App\Models\User::factory()->create([
            'name'     => 'User',
            'email'    => 'user@dev.com',
            'password' => bcrypt('password'),
        ]))->assignRole($roles['user']);
    }
}
