<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('radio_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('frequency')->nullable(); // e.g. "101.5 FM" or "1080 AM"
            $table->enum('band', ['AM', 'FM', 'ONLINE'])->default('FM');
            $table->string('genre')->nullable();
            $table->string('language')->default('English');
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_stations');
    }
};
