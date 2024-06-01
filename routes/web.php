<?php

use Illuminate\Support\Facades\Route;
use LowerRockLabs\Lockable\Http\Controllers\LockRequestController;

Route::get('/unlock/{lockRequestID}', [LockRequestController::class, 'unlock'])->name('laravellockable.unlock');
Route::get('/request/{lockRequestID}', [LockRequestController::class, 'request'])->name('laravellockable.request');
