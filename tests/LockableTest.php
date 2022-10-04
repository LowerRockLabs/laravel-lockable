<?php

use App\User;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('Can create a user', function () {
    // Prepare
    $user = User::factory()->create(['name' => 'TestUser']);

    $this->assertSame('TestUser', $user->name);
});
