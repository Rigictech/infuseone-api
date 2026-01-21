<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = [ ['name' => 'Admin','guard_name'=>'web'],
                   ['name' => 'User','guard_name'=>'web']];
        foreach($roles as $role){
            Role::create($role);
        }

        $roles = Role::whereIn('name',['Admin'])->get();
        $users = User::whereIn('id',['1','2'])->get();
        foreach($users as $user){
            foreach($roles as $role){
                $user->assignRole($role);
            }
        }
    }
}
