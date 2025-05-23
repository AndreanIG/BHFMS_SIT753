<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // Create user with the same credentials
        User::create([
            "user_name" =>'Admin 1',
            "gender" => '1',
            "email" => 'admin1@gmail.com',
            "date_of_birth" => '2000-01-01', // use ISO format for consistency
            "phone" => rand(1000000,5000000),
            "user_role_id" => '1',
            "password" => bcrypt('admin1'),
            "bank_name" => 'BCA',
            "account_number" => "115123",
        ]);

        // Perform login
        $response = $this->post('/login', [
            'email' => 'admin1@gmail.com',
            'password' => 'admin1',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated(); // checks any user is logged in
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
