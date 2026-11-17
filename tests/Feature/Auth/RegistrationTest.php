<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register and role is forced to peserta', function () {
    $response = $this->post('/register', [
        'name' => 'Peserta Baru',
        'email' => 'pesertabaru@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'panitia', // Attempt privilege escalation
    ]);

    $this->assertAuthenticated();
    $user = User::where('email', 'pesertabaru@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('peserta');
    $response->assertRedirect(route('home'));
});