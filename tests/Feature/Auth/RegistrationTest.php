<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register')->assertNotFound();
});

test('registration endpoint is not avaible', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(404);
});
