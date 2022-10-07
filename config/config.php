<?php

// config for LowerRockLabs/Lockable
return [

    // Name of the Table containing the locks
    'locks_table' => 'model_locks',

    // Name of the Table containing the lock watchers
    'lock_watchers_table' => 'model_lock_watchers',

    // Enable retrieval of lock status on retrieving a model
    'get_locked_on_retrieve' => true,

    // Prevent updating if a model is locked by another user
    'prevent_updating' => true,

    // Time in Seconds For Lock To Persist
    'duration' => '3600',

    'scheduled_task_enable' => true,

];
