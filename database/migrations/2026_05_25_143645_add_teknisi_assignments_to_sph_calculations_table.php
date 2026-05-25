<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sph_calculations', function (Blueprint $table) {
            $table->json('teknisi_assignments')->nullable()->after('catatan');
            $table->decimal('total_teknisi_upah', 15, 2)->default(0)->after('teknisi_assignments');
        });
    }

    public function down(): void
    {
        Schema::table('sph_calculations', function (Blueprint $table) {
            $table->dropColumn(['teknisi_assignments', 'total_teknisi_upah']);
        });
    }
};
