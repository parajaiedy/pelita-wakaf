<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->string('kategori')->default('Wakaf')->after('status_sertipikat');
        });

        // Seluruh data lama adalah aset wakaf.
        DB::table('aset_wakaf')->whereNull('kategori')->update(['kategori' => 'Wakaf']);
    }

    public function down(): void
    {
        Schema::table('aset_wakaf', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
