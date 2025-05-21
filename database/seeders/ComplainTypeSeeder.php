<?php

namespace Database\Seeders;

use App\Models\ComplainType;
use Illuminate\Database\Seeder;

class ComplainTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ComplainType::create([
            "id" =>'1',
            "complain_type_name" => 'Utility'
        ]);

        ComplainType::create([
            "id" =>'2',
            "complain_type_name" => 'Broken/Lost Keys'
        ]);

        ComplainType::create([
            "id" =>'3',
            "complain_type_name" => 'Noise Complaint'
        ]);

        ComplainType::create([
            "id" =>'4',
            "complain_type_name" => 'Wifi Problems'
        ]);
        ComplainType::create([
            "id" =>'5',
            "complain_type_name" => 'Other'
        ]);
    }
}
