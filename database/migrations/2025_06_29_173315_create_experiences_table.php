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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('job_title_en')->nullable();
            $table->string('job_title_tr')->nullable();
            $table->string('company_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_tr')->nullable();
            $table->meta();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
