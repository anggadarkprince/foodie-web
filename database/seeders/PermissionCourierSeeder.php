<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionCourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(
            ['permission' => Permission::COURIER_VIEW],
            ['description' => 'View user courier data', 'module' => 'courier', 'feature' => 'courier']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::COURIER_CREATE],
            ['description' => 'Create user courier data', 'module' => 'courier', 'feature' => 'courier']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::COURIER_EDIT],
            ['description' => 'Edit user courier data', 'module' => 'courier', 'feature' => 'courier']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::COURIER_DELETE],
            ['description' => 'Delete user courier data', 'module' => 'courier', 'feature' => 'courier']
        );

    }
}
