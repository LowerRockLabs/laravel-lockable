<?php

use Illuminate\Support\Facades\Route;
use LowerRockLabs\Lockable\Http\Controllers\LockRequestController;

Route::get('/laravellockable/{lockRequestID}', [LockRequestController::class, 'unlock'])->name('laravellockable.unlock');
