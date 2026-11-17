<?php

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use App\Services\MidtransService;

test('midtrans webhook rejects invalid signature with 400', function () {
    $registration = Registration::factory()->pending()->create();

    $response = $this->postJson('/midtrans/notification', [
        'order_id' => $registration->order_id,
        'status_code' => '200',
        'gross_amount' => '100000.00',
        'signature_key' => 'invalid-signature-hash',
        'transaction_status' => 'settlement',
    ]);

    $response->assertStatus(400);
    expect($registration->fresh()->status)->toBe('pending');
});

test('midtrans webhook settlement updates registration to paid with ticket_code', function () {
    $serverKey = config('services.midtrans.server_key');
    $registration = Registration::factory()->pending()->create();

    $orderId = $registration->order_id;
    $statusCode = '200';
    $grossAmount = '100000.00';
    $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    $response = $this->postJson('/midtrans/notification', [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $validSignature,
        'transaction_status' => 'settlement',
    ]);

    $response->assertStatus(200);

    $refreshed = $registration->fresh();
    expect($refreshed->status)->toBe('paid');
    expect($refreshed->ticket_code)->toStartWith('WTX-');
    expect($refreshed->paid_at)->not->toBeNull();
});

test('midtrans webhook update is idempotent', function () {
    $serverKey = config('services.midtrans.server_key');
    $registration = Registration::factory()->pending()->create();

    $orderId = $registration->order_id;
    $statusCode = '200';
    $grossAmount = '100000.00';
    $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    // First settlement
    $this->postJson('/midtrans/notification', [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $validSignature,
        'transaction_status' => 'settlement',
    ]);

    $firstTicketCode = $registration->fresh()->ticket_code;
    $firstPaidAt = $registration->fresh()->paid_at;

    // Second settlement (duplicate webhook notification)
    $this->postJson('/midtrans/notification', [
        'order_id' => $orderId,
        'status_code' => $statusCode,
        'gross_amount' => $grossAmount,
        'signature_key' => $validSignature,
        'transaction_status' => 'settlement',
    ]);

    $secondRegistration = $registration->fresh();
    expect($secondRegistration->ticket_code)->toBe($firstTicketCode);
    expect($secondRegistration->paid_at->toDateTimeString())->toBe($firstPaidAt->toDateTimeString());
});

test('midtrans check status button verifies ownership and checks api', function () {
    $user = User::factory()->create();
    $registration = Registration::factory()->pending()->create([
        'user_id' => $user->id,
    ]);

    // Mock MidtransService checkStatus
    $mockMidtrans = Mockery::mock(MidtransService::class)->makePartial();
    $mockMidtrans->shouldReceive('checkStatus')
        ->with($registration->order_id)
        ->andReturn((object)[
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);
    $this->app->instance(MidtransService::class, $mockMidtrans);

    $response = $this->actingAs($user)->post("/registrations/{$registration->id}/check-status");

    $response->assertRedirect(route('tickets.show', $registration->id));
    expect($registration->fresh()->status)->toBe('paid');
});