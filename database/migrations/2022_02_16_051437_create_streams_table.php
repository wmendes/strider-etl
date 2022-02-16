<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->string('movie_title');
            $table->string('user_email');
            $table->integer('size_mb');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->timestamps();
        });

        Schema::table('streams', function (Blueprint $table) {
            $table->unique(["movie_title", "user_email", "size_mb", "start_at", "end_at"], 'user_stream_unique');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streams');
    }
};
