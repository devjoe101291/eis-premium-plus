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
        Schema::create('eis_materials', function (Blueprint $table) {
            $table->id();
            
              $table->foreignId('topic_id')
          ->constrained('eis_topic');  // ✅ points to eis_topic.id
        //   ->cascadeOnDelete();    
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('file_type', ['file', 'url'])->nullable();
            $table->integer('file_size')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('is_active')->default(true);
            // $table->foreignId('created_by')->constrained('eis_users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eis_materials');
    }
};
