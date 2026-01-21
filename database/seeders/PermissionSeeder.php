<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
      
        $permissions = [
                        // ROLES 
                        'role-list',
                        'role-create',
                        'role-edit',
                        'role-status-update',

                       
                        //USER
                        'user-list',
                        'user-create',
                        'user-edit',
                        'user-delete',
                        'user-update-status',
                       
                    ];
        
        foreach ($permissions as $key) {
            $permission = Permission::findOrCreate($key,'web');
            $permission->save();
        }
      
        $roles = Role::whereIn('name',['Admin'])->get();
        foreach($roles as $role){
            $role->syncPermissions($permissions);
        }
 
    }
}
