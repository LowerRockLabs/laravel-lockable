<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLockWatchersTable extends Migration
{
    public function up()
    {
        Schema::create('model_lock_watchers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('model_lock_id')->unsigned()->index();
            $table->uuidMorphs('user');
            $table->timestamps();
            $table->unique(['model_lock_id', 'user_id'], 'lock_watch_unique');
            $table->foreign('model_lock_id')->references('id')->on('model_lock_watchers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_lock_watchers');
    }
}
