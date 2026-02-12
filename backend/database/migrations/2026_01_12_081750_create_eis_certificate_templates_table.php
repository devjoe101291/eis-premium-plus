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
        Schema::create('eis_certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->text('content')->comment('Certificate text content in JSON or text format');
            $table->text('e_signature')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eis_certificate_templates');
    }
};
