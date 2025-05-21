<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::create([
            "transaction_type_name"=>'Rent'
        ]);
        TransactionType::create([
            "transaction_type_name"=>'Electricty'
        ]);
        TransactionType::create([
            "transaction_type_name"=>'Water'
        ]);
        TransactionType::create([
            "transaction_type_name"=>'Down Payment'
        ]);
        TransactionType::create([
            "transaction_type_name"=>'Other'
        ]);
    }
}
