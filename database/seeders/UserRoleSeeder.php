<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create([
            "user_role_name" =>'Admin'
        ]);
        UserRole::create([
            "user_role_name" =>'Tenant'
        ]);
        UserRole::create([
            "user_role_name" =>'Owner'
        ]);
        UserRole::create([
            "user_role_name" =>'Manager'
        ]);
    }
}
