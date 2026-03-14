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
        Schema::table('register_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('register_requests', 'foto_formal')) {
                $table->string('foto_formal')->nullable()->after('foto_profil');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_requests', function (Blueprint $table) {
            $table->dropColumn('foto_formal');
        });
    }
};
