<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->string('jenis_hak')->nullable()->after('status_sertipikat');
            $table->string('nomor_hak')->nullable()->after('jenis_hak');
        });
    }

    public function down(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->dropColumn(['jenis_hak', 'nomor_hak']);
        });
    }
};