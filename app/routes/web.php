<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    phpinfo();
    exit;
});
