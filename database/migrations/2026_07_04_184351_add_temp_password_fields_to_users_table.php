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
            $table->string('temporary_password')->nullable()->after('password');
            $table->boolean('is_password_changed')->default(false)->after('temporary_password');

            $table->string('role')->index()->change();
            $table->unsignedBigInteger('company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('role');
            $table->dropIndex('company_id');
            $table->dropColumn(['temporary_password', 'is_password_changed']);
        });
    }
};
