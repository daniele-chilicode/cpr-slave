<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'Hello from slave laravel!', 'timestamp' => now()];
});
