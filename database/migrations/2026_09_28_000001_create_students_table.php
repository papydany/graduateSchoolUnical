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
            $table->integer('entry_session')->unsigned();
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername')->nullable();
            $table->string('registration_number')->unique();
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
