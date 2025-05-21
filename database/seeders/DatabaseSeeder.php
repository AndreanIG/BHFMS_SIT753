<?php

namespace Database\Seeders;

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
        $this->call([
            UserRoleSeeder::class,
            UserSeeder::class,
            BoardingTypeSeeder::class,
            PaymentMethodsSeeder::class,
            TransactionTypesSeeder::class,
            ComplainTypeSeeder::class,
            StartingSeeder::class,
        ]);

    }
}
