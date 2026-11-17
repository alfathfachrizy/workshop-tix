<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('peserta cannot access panitia registrations list', function () {
    $peserta = User::factory()->create(['role' => 'peserta']);

    $response = $this->actingAs($peserta)->get('/panitia/registrations');

    $response->assertStatus(403);
});

test('panitia can view registrations in default eager mode with minimal queries', function () {
    $panitia = User::factory()->panitia()->create();
    $workshop = Workshop::factory()->create();

    // Create 5 registrations
    Registration::factory()->count(5)->create([
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($panitia)->get('/panitia/registrations?mode=eager');

    $response->assertStatus(200);
    $response->assertSee('EAGER');
    $response->assertSee('query');
});

test('panitia can toggle to lazy mode to observe N+1 queries', function () {
    $panitia = User::factory()->panitia()->create();
    $workshop = Workshop::factory()->create();

    Registration::factory()->count(5)->create([
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($panitia)->get('/panitia/registrations?mode=lazy');

    $response->assertStatus(200);
    $response->assertSee('LAZY');
    $response->assertSee('query');
});