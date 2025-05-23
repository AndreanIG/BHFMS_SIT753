<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $user = User::create([
            "user_name" => 'Admin 1',
            "gender" => '1',
            "email" => 'admin1@gmail.com',
            "date_of_birth" => '2000-01-01',
            "phone" => rand(1000000, 5000000),
            "user_role_id" => '1',
            "password" => bcrypt('admin1'),
            "bank_name" => 'BCA',
            "account_number" => "115123",
        ]);

        $this->actingAs($user);

        $response = $this->get('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
