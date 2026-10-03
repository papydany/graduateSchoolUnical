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
        // 23 = profile not yet completed
        if (Schema::hasColumn('students', 'status')) {
            Schema::table('students', function (Blueprint $table) {
                $table->integer('status')->default(23)->change();
            });

            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->integer('status')->default(23)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
