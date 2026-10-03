<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->string('status_tindak_lanjut')->default('Belum Ditindaklanjuti')->after('status_sertipikat');
        });
    }

    public function down(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->dropColumn('status_tindak_lanjut');
        });
    }
};
