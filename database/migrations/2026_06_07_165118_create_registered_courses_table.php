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
        Schema::create('registered_courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('course_id')->unsigned();
            $table->integer('programme_of_study_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->string('code')->unique();
            $table->string('title');
            $table->integer('unit')->unsigned();
            $table->integer('semester')->unsigned();
            $table->integer('session')->unsigned();
            $table->string('status');
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registered_courses');
    }
};
