<?php

namespace LowerRockLabs\Lockable\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use LowerRockLabs\Lockable\Models\ModelLock;
use LowerRockLabs\Lockable\Models\ModelLockWatcher;

class LockRequestController extends BaseController
{
    public function unlock($lockRequestID)
    {
        try {
            $modelLock = ModelLock::findOrFail($lockRequestID);
            if (isset($modelLock->user_id)) {
                if ($modelLock->user_id == Auth::id()) {
                    $modelLock->delete(); //delees
                }
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('message', __('error.modelnotfound'));

            return false;
        }
    }

    public function request($lockRequestID)
    {
        if (! empty(Auth::user())) {
            $mlw = new ModelLockWatcher;
            $mlw->model_lock_id = $lockRequestID;
            $mlw->user_id = Auth::id();
            $mlw->user_type = get_class(Auth::user());
            $mlw->save();
        }
    }
}
