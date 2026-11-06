<?php

test('registration screen can be rendered', function () {
    \App\Models\Barangay::create(['name' => 'Test Barangay']);

    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Test Barangay');
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('citizen.dashboard', absolute: false));
});
