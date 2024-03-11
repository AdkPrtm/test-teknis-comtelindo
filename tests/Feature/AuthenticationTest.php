<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_redirect_when_not_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_register_success(): void
    {
        $response = $this->post('/register', [
            'name' => 'Andhika Pratama',
            'email' => 'andhika@gmail.com',
            'password' => 'Rahasia123',
        ]);

        $response->assertViewIs('auth.login');
        $response->assertViewHas('success', 'Registration successfull! Please login');
    }

    public function test_register_failed_email_already_used(): void
    {

        $this->test_register_success();

        $response = $this->post('/register', [
            'name' => 'Andhika Pratama',
            'email' => 'andhika@gmail.com',
            'password' => 'Rahasia123',
        ]);

        $response->assertViewIs('auth.register')
            ->assertViewHas('errors', function ($errors) {
                return $errors->get('errors')[0] === 'Your email has been registered!';
            });
    }

    public function test_user_registration_with_invalid_email()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'andhika@example.com',
            'password' => 'Rahasia123',
        ];

        $validator = Validator::make($userData, [
            'name' => 'required|string',
            'email' => 'required|email:dns',
            'password' => 'required|string|min:6',
        ]);

        $this->assertTrue($validator->fails());

        $this->assertTrue($validator->errors()->has('email'));

        $response = $this->post('/register', $userData);

        $response->assertViewIs('auth.register')
            ->assertViewHas('errors', function ($errors) {
                return $errors instanceof \Illuminate\Support\ViewErrorBag &&
                    $errors->getBag('default')->has('email') &&
                    $errors->getBag('default')->first('email') === 'The email field must be a valid email address.';
            });
    }

    public function test_login_success(): void
    {
        $this->test_register_success();
        $userData = [
            'email' => 'andhika@gmail.com',
            'password' => 'Rahasia123',
        ];

        $response = $this->post('/login', $userData);

        $this->assertTrue(Session::has('_token'));
        $response->assertRedirect('/');
    }

    public function test_login_wrong_credential(): void
    {
        $this->test_register_success();
        $userData = [
            'email' => 'andhika@gmail.com',
            'password' => 'yayahahaha',
        ];

        $response = $this->post('/login', $userData);

        $response->assertStatus(302)->assertRedirect();
        $this->assertTrue(Session::has('loginError'));
    }

    public function test_user_logout()
    {
        $this->test_login_success();
        
        $this->assertTrue(Auth::check());

        $response = $this->post('/logout');

        $response->assertRedirect('/login');

        $this->assertFalse(Auth::check());

        $this->assertFalse(Session::has(Auth::getName()));

        $this->assertNotNull(Session::token());
    }
}
