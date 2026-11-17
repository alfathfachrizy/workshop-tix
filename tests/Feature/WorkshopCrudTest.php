<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;

test('public can view workshop listing on landing page', function () {
    $workshop = Workshop::factory()->create([
        'title' => 'Web Security Workshop',
        'price' => 50000,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Web Security Workshop');
});

test('public can view workshop detail page', function () {
    $workshop = Workshop::factory()->create([
        'title' => 'AI Deep Learning Workshop',
        'slug' => 'ai-deep-learning-workshop',
    ]);

    $response = $this->get('/workshops/ai-deep-learning-workshop');

    $response->assertStatus(200);
    $response->assertSee('AI Deep Learning Workshop');
});

test('panitia can create a new workshop', function () {
    $panitia = User::factory()->panitia()->create();

    $response = $this->actingAs($panitia)->post('/panitia/workshops', [
        'title' => 'Laravel RESTful API Bootcamp',
        'description' => 'Complete guide to building REST APIs with Laravel 11.',
        'speaker' => 'Dr. John Doe',
        'location' => 'Lab Komputer 1 SMK PESAT',
        'starts_at' => now()->addDays(5)->toDateTimeString(),
        'price' => 75000,
        'quota' => 25,
    ]);

    $response->assertRedirect(route('panitia.workshops.index'));
    $this->assertDatabaseHas('workshops', [
        'title' => 'Laravel RESTful API Bootcamp',
        'price' => 75000,
    ]);
});

test('panitia cannot lower quota below registered count', function () {
    $panitia = User::factory()->panitia()->create();
    $workshop = Workshop::factory()->create(['quota' => 20]);

    // Create 5 registrations for this workshop
    Registration::factory()->count(5)->create([
        'workshop_id' => $workshop->id,
    ]);

    // Attempt to lower quota to 3 (less than 5)
    $response = $this->actingAs($panitia)->put("/panitia/workshops/{$workshop->id}", [
        'title' => $workshop->title,
        'description' => $workshop->description,
        'speaker' => $workshop->speaker,
        'location' => $workshop->location,
        'starts_at' => $workshop->starts_at->toDateTimeString(),
        'price' => $workshop->price,
        'quota' => 3,
    ]);

    $response->assertSessionHasErrors('quota');
    expect($workshop->fresh()->quota)->toBe(20);
});

test('panitia can delete workshop', function () {
    $panitia = User::factory()->panitia()->create();
    $workshop = Workshop::factory()->create();

    $response = $this->actingAs($panitia)->delete("/panitia/workshops/{$workshop->id}");

    $response->assertRedirect(route('panitia.workshops.index'));
    $this->assertDatabaseMissing('workshops', [
        'id' => $workshop->id,
    ]);
});

test('panitia can view registrants of workshop', function () {
    $panitia = User::factory()->panitia()->create();
    $workshop = Workshop::factory()->create();
    $peserta = User::factory()->create(['name' => 'Peserta Uji']);

    Registration::factory()->create([
        'user_id' => $peserta->id,
        'workshop_id' => $workshop->id,
    ]);

    $response = $this->actingAs($panitia)->get("/panitia/workshops/{$workshop->id}/registrants");

    $response->assertStatus(200);
    $response->assertSee('Peserta Uji');
});

test('peserta cannot access panitia workshop crud', function () {
    $peserta = User::factory()->create(['role' => 'peserta']);
    $workshop = Workshop::factory()->create();

    $this->actingAs($peserta)->get('/panitia/workshops')->assertStatus(403);
    $this->actingAs($peserta)->get('/panitia/workshops/create')->assertStatus(403);
    $this->actingAs($peserta)->post('/panitia/workshops', [])->assertStatus(403);
    $this->actingAs($peserta)->delete("/panitia/workshops/{$workshop->id}")->assertStatus(403);
});