<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Guarded with hasColumn/hasIndex because these changes were first made
     * directly on existing databases.
     */
    public function up(): void
    {
        Schema::table('registered_courses', function (Blueprint $table) {
            if (! Schema::hasColumn('registered_courses', 'specialization_id')) {
                $table->integer('specialization_id')->after('programme_of_study_id');
            }
            if (! Schema::hasColumn('registered_courses', 'programme_type_id')) {
                $table->integer('programme_type_id')->after('specialization_id');
            }
            if (Schema::hasColumn('registered_courses', 'status')) {
                $table->dropColumn('status');
            }
            // The same course code is registered for many programmes/sessions.
            if (Schema::hasIndex('registered_courses', 'registered_courses_code_unique')) {
                $table->dropUnique('registered_courses_code_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registered_courses', function (Blueprint $table) {
            $table->dropColumn(['specialization_id', 'programme_type_id']);
            $table->string('status')->default('active');
        });
    }
};
