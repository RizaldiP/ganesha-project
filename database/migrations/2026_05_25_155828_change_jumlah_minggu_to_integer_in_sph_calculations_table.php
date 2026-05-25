<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sph_calculations', function (Blueprint $table) {
            $table->integer('jumlah_minggu_transport')->default(4)->change();
            $table->integer('jumlah_minggu_harian')->default(4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sph_calculations', function (Blueprint $table) {
            $table->decimal('jumlah_minggu_transport', 5, 1)->default(4)->change();
            $table->decimal('jumlah_minggu_harian', 5, 1)->default(4)->change();
        });
    }
};
