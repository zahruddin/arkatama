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
        Schema::create('penumpangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_id')->constrained('travels');
            $table->string('kode_booking', 12);
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kota');
            $table->integer('usia');
            $table->year('tahun_lahir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penumpangs');
    }
};
