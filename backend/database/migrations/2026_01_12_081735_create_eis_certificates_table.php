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
        Schema::create('eis_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('eis_users')->onDelete('cascade');
            $table->foreignId('fk_exam_id')->constrained('eis_exams')->onDelete('cascade');
            $table->string('certificate_path');
            $table->string('certificate_number')->unique();
            $table->timestamp('issued_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('fk_exam_id');
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eis_certificates');
    }
};
