<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create([
            "payment_method_name"=>"QRIS",
            "status" =>"disable"
        ]);
        PaymentMethod::create([
            "payment_method_name"=>"BCA",
            "status" =>"available"
        ]);
        PaymentMethod::create([
            "payment_method_name"=>"Mandiri",
            "status" =>"available"
        ]);
    }
}
