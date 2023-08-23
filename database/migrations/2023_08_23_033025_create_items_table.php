<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('type');
            $table->integer('hours_meter');
            $table->integer('capacity');
            $table->string('engine');
            $table->integer('lifting_height');
            $table->integer('stage');
            $table->integer('load_center');
            $table->tinyInteger('status');
            $table->foreignId('mekanik_id')->references('id')->on('users')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
