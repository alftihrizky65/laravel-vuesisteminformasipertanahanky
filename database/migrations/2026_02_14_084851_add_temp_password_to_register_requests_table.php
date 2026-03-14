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
            $table->string('temp_password', 255)->nullable()->after('diproses_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_requests', function (Blueprint $table) {
            $table->dropColumn('temp_password');
        });
    }
};
