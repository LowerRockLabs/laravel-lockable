<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLocksTable extends Migration
{
    public function up()
    {
        Schema::create('model_locks', function (Blueprint $table) {
            $table->id();
            $table->morphs('lockable');
            $table->uuid('user_id')->index();
            $table->unique(['lockable_id', 'lockable_type'], 'lockable_unique');
            $table->timestamp('expires_at');
            $table->timestamps();
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
        Schema::dropIfExists('model_locks');
    }
};
