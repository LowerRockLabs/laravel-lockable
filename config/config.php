<?php

// config for LowerRockLabs/Lockable
return [

    // Name of the Table containing the locks
    'locks_table' => 'model_locks',
    'lock_watchers_table' => 'model_lock_watchers',
    'get_locked_on_retrieve' => true,
    'prevent_updating' => true,

    // Time in Seconds For Lock To Persist
    'duration' => '3600',

];
