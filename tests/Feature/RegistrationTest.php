<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('guest cannot register workshop and is redirected to login', function () {
    $workshop = Workshop::factory()->create();

    $response = $this->post("/workshops/{$workshop->id}/register");

    $response->assertRedirect('/login');
});

test('peserta can register for an available workshop', function () {
    $peserta = User::factory()->create();
    $workshop = Workshop::factory()->create([
        'price' => 75000,
        'quota' => 20,
    ]);

    $response = $this->actingAs($peserta)->post("/workshops/{$workshop->id}/register");

    $this->assertDatabaseHas('registrations', [
        'user_id' => $peserta->id,
        'workshop_id' => $workshop->id,
        'status' => 'pending',
    ]);

    $registration = Registration::where('user_id', $peserta->id)->where('workshop_id', $workshop->id)->first();
    $response->assertRedirect(route('registrations.show', $registration->id));
});

test('duplicate registration is rejected and redirects existing pending registration', function () {
    $peserta = User::factory()->create();
    $workshop = Workshop::factory()->create();

    $existing = Registration::factory()->pending()->create([
        'user_id' => $peserta->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($peserta)->post("/workshops/{$workshop->id}/register");

    $response->assertRedirect(route('registrations.show', $existing->id));
    $response->assertSessionHas('warning');
    expect(Registration::where('user_id', $peserta->id)->where('workshop_id', $workshop->id)->count())->toBe(1);
});

test('registration is rejected when workshop quota is full', function () {
    $workshop = Workshop::factory()->create(['quota' => 1]);

    // Fill the quota with a paid registration
    Registration::factory()->paid()->create([
        'workshop_id' => $workshop->id,
    ]);

    $peserta = User::factory()->create();
    $response = $this->actingAs($peserta)->post("/workshops/{$workshop->id}/register");

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('registrations', [
        'user_id' => $peserta->id,
        'workshop_id' => $workshop->id,
    ]);
});

test('peserta can view own registrations list', function () {
    $peserta = User::factory()->create();
    $workshop = Workshop::factory()->create(['title' => 'Flutter Bootcamp']);

    Registration::factory()->create([
        'user_id' => $peserta->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($peserta)->get('/my-registrations');

    $response->assertStatus(200);
    $response->assertSee('Flutter Bootcamp');
});

test('peserta cannot view another users registration details and gets 403', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $workshop = Workshop::factory()->create();

    $registration = Registration::factory()->create([
        'user_id' => $user1->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($user2)->get("/registrations/{$registration->id}");

    $response->assertStatus(403);
});