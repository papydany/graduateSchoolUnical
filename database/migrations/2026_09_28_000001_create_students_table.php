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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('faculty_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('programme_id')->unsigned();
            $table->integer('programme_of_study_id')->nullable()->unsigned();
            $table->integer('specialization_id')->nullable()->unsigned();
            $table->integer('entry_session')->unsigned();
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername')->nullable();
            $table->string('registration_number')->unique();
            $table->string('matriculation_number')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('country')->nullable();
            $table->integer('state')->nullable();
            $table->integer('lga')->nullable();
            $table->string('image_url')->nullable();
            $table->string('matrital_status')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
