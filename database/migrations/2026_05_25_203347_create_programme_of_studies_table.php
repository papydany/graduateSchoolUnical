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
        if (Schema::hasTable('programme_of_studies')) {
            return; // table already exists, nothing to do
        }

        Schema::create('programme_of_studies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('degree_type');        // e.g. M.Sc, Ph.D, M.A, M.Eng
            $table->string('department');
            $table->unsignedTinyInteger('duration')->default(2)->comment('Duration in years');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_of_studies');
    }
};
