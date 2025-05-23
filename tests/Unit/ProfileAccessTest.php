<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_profile_page()
    {
        $user = User::create([
            "user_name" => 'Profile User',
            "gender" => '1',
            "email" => 'profile@example.com',
            "date_of_birth" => '1990-05-01',
            "phone" => '123456789',
            "user_role_id" => '2',
            "password" => bcrypt('securepass'),
            "bank_name" => 'CIMB',
            "account_number" => '998877',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }
}
