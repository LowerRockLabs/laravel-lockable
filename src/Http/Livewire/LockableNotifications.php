<?php

/**
 * This file contains the Base.
 *
 * @category  LRLSystems
 * @package   Lower_Rock_Labs_Command_And_Control
 * @author    Lower Rock Labs <dev@lowerrocklabs.com>
 * @copyright 2020 Lower Rock Labs
 * @license   All rights served
 * @link      https://www.lowerrocklabs.com
 */

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LockableNotifications extends Component
{
    public function render()
    {
        return view('livewire.lockablenotifications');
    }

    public function releaseLockByID($lockID)
    {
        // set the flag to make sure that locks can be released
        $modelLockInst = ModelLock::find($lockID);
        if ($modelLockInst->user_id == Auth::id()) {
            $modelLockInst->delete();
            return true;
        }
        return false;
    }
}
