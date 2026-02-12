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
Schema::create('eis_exams', function (Blueprint $table) {
    $table->id(); // ✅ default primary key name: id

    $table->unsignedBigInteger('fk_topic_id');
    $table->string('title', 255);
    $table->text('instructions')->nullable();
    $table->decimal('passing_rate', 8, 2)->nullable();
    $table->json('question_json')->nullable();
    $table->json('answers_json')->nullable();
    $table->integer('time_limit')->nullable();
    $table->tinyInteger('status')->default(0)->comment('0 = Active, 1 = Inactive');
    $table->timestamps();

    $table->foreign('fk_topic_id')
          ->references('id')
          ->on('eis_topic')
          ->cascadeOnDelete();

    $table->index('fk_topic_id');
    $table->index('status');
});

}

public function down(): void
{
    Schema::dropIfExists('eis_exams');
}

};
