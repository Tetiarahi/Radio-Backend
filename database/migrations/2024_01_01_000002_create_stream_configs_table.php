<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radio_station_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // e.g. "128kbps MP3", "64kbps AAC"
            $table->string('stream_url');
            $table->enum('stream_type', ['icecast', 'shoutcast', 'hls', 'other'])->default('icecast');
            $table->string('codec')->nullable(); // mp3, aac, opus
            $table->integer('bitrate')->nullable(); // in kbps
            $table->boolean('is_https')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('metadata_url')->nullable(); // Icecast /status-json.xsl or Shoutcast /7.html
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stream_configs');
    }
};
