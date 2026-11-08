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
        Schema::create('short_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->text('question');
            $table->unsignedInteger('mark')->default(2);
            $table->text('answer');
            $table->string('figure')->nullable();
            $table->boolean('figure_on_top')->default(false);
            $table->timestamps();
        });

        Schema::create('mcq_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->text('title');
            $table->unsignedInteger('mark')->default(1);
            $table->string('figure')->nullable();
            $table->boolean('figure_on_top')->default(false);

            $table->string('option_a');
            $table->string('option_a_figure')->nullable();
            $table->boolean('option_a_figure_on_top')->default(false);

            $table->string('option_b');
            $table->string('option_b_figure')->nullable();
            $table->boolean('option_b_figure_on_top')->default(false);

            $table->string('option_c');
            $table->string('option_c_figure')->nullable();
            $table->boolean('option_c_figure_on_top')->default(false);

            $table->string('option_d');
            $table->string('option_d_figure')->nullable();
            $table->boolean('option_d_figure_on_top')->default(false);

            $table->string('answer_option', 10);
            $table->text('short_explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('creative_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->text('title');
            $table->string('figure')->nullable();
            $table->boolean('figure_on_top')->default(false);

            $table->text('question_a');
            $table->unsignedInteger('mark_a')->default(1);
            $table->string('figure_a')->nullable();
            $table->boolean('figure_a_on_top')->default(false);

            $table->text('question_b');
            $table->unsignedInteger('mark_b')->default(2);
            $table->string('figure_b')->nullable();
            $table->boolean('figure_b_on_top')->default(false);

            $table->text('question_c');
            $table->unsignedInteger('mark_c')->default(3);
            $table->string('figure_c')->nullable();
            $table->boolean('figure_c_on_top')->default(false);

            $table->text('question_d');
            $table->unsignedInteger('mark_d')->default(4);
            $table->string('figure_d')->nullable();
            $table->boolean('figure_d_on_top')->default(false);

            $table->unsignedInteger('full_mark')->default(10);
            $table->text('answer_explanation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_questions');
        Schema::dropIfExists('mcq_questions');
        Schema::dropIfExists('short_questions');
    }
};
