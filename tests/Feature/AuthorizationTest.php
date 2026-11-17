<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('peserta cannot access panitia dashboard and gets 403', function () {
    $peserta = User::factory()->create(['role' => 'peserta']);

    $response = $this->actingAs($peserta)->get('/panitia');

    $response->assertStatus(403);
});

test('guest cannot access panitia dashboard and is redirected to login', function () {
    $response = $this->get('/panitia');

    $response->assertRedirect('/login');
});

test('panitia can access panitia dashboard', function () {
    $panitia = User::factory()->panitia()->create();

    $response = $this->actingAs($panitia)->get('/panitia');

    $response->assertStatus(200);
});

test('peserta cannot view or pay another user registration', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $workshop = Workshop::factory()->create();

    $registration = Registration::factory()->create([
        'user_id' => $user1->id,
        'workshop_id' => $workshop->id,
    ]);

    expect($user2->can('view', $registration))->toBeFalse();
    expect($user2->can('pay', $registration))->toBeFalse();
    expect($user2->can('viewTicket', $registration))->toBeFalse();

    expect($user1->can('view', $registration))->toBeTrue();
    expect($user1->can('pay', $registration))->toBeTrue();
    expect($user1->can('viewTicket', $registration))->toBeTrue();
});