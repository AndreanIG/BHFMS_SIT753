<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileGuestAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }
}
