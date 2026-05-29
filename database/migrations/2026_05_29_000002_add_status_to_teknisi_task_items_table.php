<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teknisi_task_items', function (Blueprint $table) {
            $table->enum('status', ['pending', 'progress', 'done'])->default('pending')->after('is_checked');
        });
    }

    public function down(): void
    {
        Schema::table('teknisi_task_items', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
