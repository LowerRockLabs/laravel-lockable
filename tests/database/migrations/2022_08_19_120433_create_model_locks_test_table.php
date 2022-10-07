<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLocksTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_locks', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('lockable');
            $table->uuid('user_id')->index();
            $table->string('user_type');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->unique(['lockable_id', 'lockable_type'], 'lockable_unique');
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('model_lock_watchers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('model_lock_id')->unsigned()->index();
            $table->uuid('user_id')->index();
            $table->string('user_type');
            $table->timestamps();
            $table->unique(['model_lock_id', 'user_id'], 'lock_watch_unique');
            $table->foreign('model_lock_id')->references('id')->on('model_lock_watchers')->onDelete('cascade');
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('model_lock_watchers');
        Schema::dropIfExists('notes');
    }
}
