<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('unpaid registration cannot view ticket and is redirected with message', function () {
    $user = User::factory()->create();
    $workshop = Workshop::factory()->create();

    $registration = Registration::factory()->pending()->create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($user)->get("/tickets/{$registration->id}");

    $response->assertRedirect(route('registrations.show', $registration->id));
    $response->assertSessionHas('warning');
});

test('user cannot view another users ticket and gets 403', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $workshop = Workshop::factory()->create();

    $registration = Registration::factory()->paid()->create([
        'user_id' => $owner->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($otherUser)->get("/tickets/{$registration->id}");

    $response->assertStatus(403);
});

test('paid user can view their digital ticket with qr code and workshop info', function () {
    $user = User::factory()->create(['name' => 'Peserta Tiket']);
    $workshop = Workshop::factory()->create([
        'title' => 'Advanced Microservices Workshop',
    ]);

    $registration = Registration::factory()->paid()->create([
        'user_id' => $user->id,
        'workshop_id' => $workshop->id,
        'ticket_code' => 'WTX-TEST1234',
    ]);

    $response = $this->actingAs($user)->get("/tickets/{$registration->id}");

    $response->assertStatus(200);
    $response->assertSee('WTX-TEST1234');
    $response->assertSee('Peserta Tiket');
    $response->assertSee('Advanced Microservices Workshop');
    $response->assertSee('<svg', false); // QR code SVG rendered
});