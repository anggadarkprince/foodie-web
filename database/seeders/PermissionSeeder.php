<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(
            ['permission' => Permission::GROUP_VIEW],
            ['description' => 'View user group data', 'module' => 'user-access', 'feature' => 'group']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::GROUP_CREATE],
            ['description' => 'Create user group data', 'module' => 'user-access', 'feature' => 'group']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::GROUP_EDIT],
            ['description' => 'Edit user group data', 'module' => 'user-access', 'feature' => 'group']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::GROUP_DELETE],
            ['description' => 'Delete user group data', 'module' => 'user-access', 'feature' => 'group']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::USER_VIEW],
            ['description' => 'View user account data', 'module' => 'user-access', 'feature' => 'user']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::USER_CREATE],
            ['description' => 'Create user account data', 'module' => 'user-access', 'feature' => 'user']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::USER_EDIT],
            ['description' => 'Edit user account data', 'module' => 'user-access', 'feature' => 'user']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::USER_DELETE],
            ['description' => 'Delete user account data', 'module' => 'user-access', 'feature' => 'user']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::CATEGORY_VIEW],
            ['description' => 'View category cuisine data', 'module' => 'restaurant', 'feature' => 'category']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CATEGORY_CREATE],
            ['description' => 'Create category cuisine data', 'module' => 'restaurant', 'feature' => 'category']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CATEGORY_EDIT],
            ['description' => 'Edit category cuisine data', 'module' => 'restaurant', 'feature' => 'category']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CATEGORY_DELETE],
            ['description' => 'Delete category cuisine data', 'module' => 'restaurant', 'feature' => 'category']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::RESTAURANT_VIEW],
            ['description' => 'View restaurant data', 'module' => 'restaurant', 'feature' => 'restaurant']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::RESTAURANT_CREATE],
            ['description' => 'Create restaurant data', 'module' => 'restaurant', 'feature' => 'restaurant']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::RESTAURANT_EDIT],
            ['description' => 'Edit restaurant data', 'module' => 'restaurant', 'feature' => 'restaurant']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::RESTAURANT_DELETE],
            ['description' => 'Delete restaurant data', 'module' => 'restaurant', 'feature' => 'restaurant']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::CUISINE_VIEW],
            ['description' => 'View cuisine data', 'module' => 'restaurant', 'feature' => 'cuisine']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CUISINE_CREATE],
            ['description' => 'Create cuisine data', 'module' => 'restaurant', 'feature' => 'cuisine']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CUISINE_EDIT],
            ['description' => 'Edit cuisine data', 'module' => 'restaurant', 'feature' => 'cuisine']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::CUISINE_DELETE],
            ['description' => 'Delete cuisine data', 'module' => 'restaurant', 'feature' => 'cuisine']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::ORDER_VIEW],
            ['description' => 'View order data', 'module' => 'transaction', 'feature' => 'order']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::ORDER_CREATE],
            ['description' => 'Create order data', 'module' => 'transaction', 'feature' => 'order']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::ORDER_EDIT],
            ['description' => 'Edit order data', 'module' => 'transaction', 'feature' => 'order']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::ORDER_DELETE],
            ['description' => 'Delete order data', 'module' => 'transaction', 'feature' => 'order']
        );

        Permission::firstOrCreate(
            ['permission' => Permission::ACCOUNT_EDIT],
            ['description' => 'Edit account data', 'module' => 'preferences', 'feature' => 'account']
        );
        Permission::firstOrCreate(
            ['permission' => Permission::SETTING_EDIT],
            ['description' => 'Delete order data', 'module' => 'preferences', 'feature' => 'setting']
        );

    }
}
