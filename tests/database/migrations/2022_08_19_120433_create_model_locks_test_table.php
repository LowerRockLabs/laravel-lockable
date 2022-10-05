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
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->unique(['lockable_id', 'lockable_type'], 'lockable_unique');
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
}
