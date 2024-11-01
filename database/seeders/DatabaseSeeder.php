<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // call the permission seeder
        $this->call(PermissionTableSeeder::class);
        // create a super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'lk911game@gmail.com',
            'password' => bcrypt('123456'),
            'email_verified_at' => now(),
        ]);
        // create the super admin role
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        // assign all permissions to the super admin role
        $superAdminRole->givePermissionTo(Permission::all());
        // assign the super admin role to the super admin
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->save();
    }
}
