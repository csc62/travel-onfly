<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:test-user', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'teste@teste2.com',
        'password' => bcrypt('12345678')
    ]);

    $token = $user->createToken('API Token')->plainTextToken;
    $this->info("Token gerado: $token");
});

Artisan::command('make:test-user-admin', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'cassio@teste.com',
        'password' => bcrypt('12345678'),
        'is_admin' => true
    ]);

    $token = $user->createToken('API Token')->plainTextToken;
    $this->info("Token gerado: $token");
});
