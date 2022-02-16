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
        Schema::create('extractions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('datasource');
            $table->enum('status', ['init', 'extracting', 'extracted', 'transforming', 'transformed', 'loading', 'loaded', 'cleaning', 'cleaned', 'finished', 'failed'])->default('init');
            $table->timestamps();
            $table->timestamp('extracted_at')->nullable();
            $table->timestamp('transformed_at')->nullable();
            $table->timestamp('loaded_at')->nullable();
            $table->timestamp('cleaned_at')->nullable();
            $table->timestamp('finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extractions');
    }
};
