<?php

namespace Database\Seeders;

use App\Models\BoardingType;
use Illuminate\Database\Seeder;

class BoardingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoardingType::create([
            "boarding_type_name" =>'Mansion'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'House'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'Apartment'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'Cabin'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'Condo'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'Duplex'
        ]);
        BoardingType::create([
            "boarding_type_name" =>'Tiny Home'
        ]);
    }
}
