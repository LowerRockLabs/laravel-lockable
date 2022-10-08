<?php

namespace LowerRockLabs\Lockable\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use LowerRockLabs\Lockable\Models\ModelLock;

class Controller extends BaseController
{
    public function unlock($lockRequestID)
    {
        try {
            $modelLock = ModelLock::findOrFail($lockRequestID);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('message', __('error.modelnotfound'));
            return false;
        }
        $modelLock->delete();
    }
}
