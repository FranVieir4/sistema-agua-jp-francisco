<?php

require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$app = require __DIR__.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@example.com';
$user = User::where('email', $email)->first();
if (! $user) {
    User::create([
        'name' => 'Administrador',
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    echo "created\n";
} else {
    echo "exists\n";
}
