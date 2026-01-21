<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::factory(2)->create()->each(function($user) {            
            //assign role as User
            $user->assignRole('Admin');
            //save user profile
        });
        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::pluck('id','name')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
