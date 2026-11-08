<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'class_id',
    'subject_id',
    'chapter_id',
    'title',
    'figure',
    'figure_on_top',
    'question_a',
    'mark_a',
    'figure_a',
    'figure_a_on_top',
    'question_b',
    'mark_b',
    'figure_b',
    'figure_b_on_top',
    'question_c',
    'mark_c',
    'figure_c',
    'figure_c_on_top',
    'question_d',
    'mark_d',
    'figure_d',
    'figure_d_on_top',
    'full_mark',
    'answer_explanation',
])]
class CreativeQuestion extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'figure_on_top' => 'boolean',
            'mark_a' => 'integer',
            'figure_a_on_top' => 'boolean',
            'mark_b' => 'integer',
            'figure_b_on_top' => 'boolean',
            'mark_c' => 'integer',
            'figure_c_on_top' => 'boolean',
            'mark_d' => 'integer',
            'figure_d_on_top' => 'boolean',
            'full_mark' => 'integer',
        ];
    }

    /**
     * Get the class that owns the question.
     *
     * @return BelongsTo<AcademicClass, $this>
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Get the subject that owns the question.
     *
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the chapter that owns the question.
     *
     * @return BelongsTo<Chapter, $this>
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}
