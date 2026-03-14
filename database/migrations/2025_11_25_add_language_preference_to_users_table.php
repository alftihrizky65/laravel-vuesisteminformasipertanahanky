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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'language_preference')) {
                $table->string('language_preference', 10)->default('id')->after('password');
            }
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('language_preference');
            }
            if (!Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('profile_photo_path');
            }
            if (!Schema::hasColumn('users', 'invite_code')) {
                $table->string('invite_code')->nullable()->unique()->after('is_approved');
            }
            if (!Schema::hasColumn('users', 'registration_reason')) {
                $table->text('registration_reason')->nullable()->after('invite_code');
            }
            if (!Schema::hasColumn('users', 'login_count')) {
                $table->unsignedInteger('login_count')->default(0)->after('registration_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'language_preference',
                'profile_photo_path',
                'is_approved',
                'invite_code',
                'registration_reason',
                'login_count',
            ]);
        });
    }
};
