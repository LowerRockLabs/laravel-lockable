<?php

namespace LowerRockLabs\Lockable\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use LowerRockLabs\Lockable\Models\ModelLock;

class LockRequestController extends BaseController
{
    public function unlock($lockRequestID)
    {
        try {
            $modelLock = ModelLock::findOrFail($lockRequestID);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('message', __('error.modelnotfound'));
            return false;
        }
        if ($modelLock->user_id == Auth:id())
        {
            $modelLock->delete();
        }
    }
}
