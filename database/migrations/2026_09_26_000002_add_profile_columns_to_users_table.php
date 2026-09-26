<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns the User model relies on that the base users migration lacks.
     */
    private array $columns = [
        'uuid', 'user_id', 'title', 'active', 'role_id', 'faculty_id', 'department_id',
    ];

    /**
     * Run the migrations.
     *
     * Guarded with hasColumn because these columns were first added
     * directly on existing databases.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'uuid')) {
                $table->string('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('users', 'user_id')) {
                $table->integer('user_id')->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('users', 'active')) {
                $table->integer('active')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->integer('role_id')->nullable()->after('active');
            }
            if (! Schema::hasColumn('users', 'faculty_id')) {
                $table->integer('faculty_id')->nullable()->after('role_id');
            }
            if (! Schema::hasColumn('users', 'department_id')) {
                $table->integer('department_id')->nullable()->after('faculty_id');
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn($this->columns);
            $table->dropSoftDeletes();
        });
    }
};
