<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_documents', function (Blueprint $table) {
            $table->string('daftar_belanja_file')->nullable()->after('spektek_file');
            $table->string('pengumuman_lelang_file')->nullable()->after('daftar_belanja_file');
            $table->string('ba_mulai_file')->nullable()->after('pengumuman_lelang_file');
            $table->string('ba_selesai_file')->nullable()->after('ba_mulai_file');

            $table->boolean('dokpra')->default(false)->after('ba_selesai_file');
            $table->boolean('dok_tagihan')->default(false)->after('dokpra');
            $table->boolean('lelang')->default(false)->after('dok_tagihan');
        });
    }

    public function down(): void
    {
        Schema::table('work_documents', function (Blueprint $table) {
            $table->dropColumn([
                'daftar_belanja_file',
                'pengumuman_lelang_file',
                'ba_mulai_file',
                'ba_selesai_file',
                'dokpra',
                'dok_tagihan',
                'lelang',
            ]);
        });
    }
};
