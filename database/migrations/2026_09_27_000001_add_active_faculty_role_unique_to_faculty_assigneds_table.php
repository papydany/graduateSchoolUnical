<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A faculty may hold only one active assignment per role. A plain
     * unique index on (faculty_id, role_id) would also count soft-deleted
     * rows, so the index includes a generated column that is 1 for active
     * rows and NULL once deleted — MySQL allows repeated NULLs in a
     * unique index, leaving removed assignments out of the constraint.
     */
    public function up(): void
    {
        Schema::table('faculty_assigneds', function (Blueprint $table) {
            $table->tinyInteger('active_assignment')
                ->nullable()
                ->storedAs('IF(deleted_at IS NULL, 1, NULL)')
                ->after('deleted_at');

            $table->unique(
                ['faculty_id', 'role_id', 'active_assignment'],
                'faculty_assigneds_faculty_role_active_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty_assigneds', function (Blueprint $table) {
            $table->dropUnique('faculty_assigneds_faculty_role_active_unique');
            $table->dropColumn('active_assignment');
        });
    }
};
