<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sph_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->string('tipe'); // 'dalam_kota' or 'luar_kota'
            $table->string('nomor_sph')->nullable();
            $table->date('tanggal_sph')->nullable();

            // Biaya components
            $table->decimal('honorarium', 15, 2)->default(0);
            $table->decimal('material', 15, 2)->default(0);
            $table->decimal('alat', 15, 2)->default(0);
            $table->decimal('upah', 15, 2)->default(0);
            $table->decimal('transport', 15, 2)->default(0);
            $table->decimal('uang_harian', 15, 2)->default(0);
            $table->decimal('akomodasi', 15, 2)->nullable()->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            $table->string('biaya_lain_keterangan')->nullable();

            // Overhead & profit
            $table->decimal('overhead', 15, 2)->default(0);
            $table->decimal('margin_keuntungan', 5, 2)->default(0); // percentage

            // Calculated totals
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->decimal('harga_penawaran', 15, 2)->default(0);

            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sph_calculations');
    }
};
