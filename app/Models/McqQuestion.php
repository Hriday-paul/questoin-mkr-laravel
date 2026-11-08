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
    'mark',
    'figure',
    'figure_on_top',
    'option_a',
    'option_a_figure',
    'option_a_figure_on_top',
    'option_b',
    'option_b_figure',
    'option_b_figure_on_top',
    'option_c',
    'option_c_figure',
    'option_c_figure_on_top',
    'option_d',
    'option_d_figure',
    'option_d_figure_on_top',
    'answer_option',
    'short_explanation',
])]
class McqQuestion extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mark' => 'integer',
            'figure_on_top' => 'boolean',
            'option_a_figure_on_top' => 'boolean',
            'option_b_figure_on_top' => 'boolean',
            'option_c_figure_on_top' => 'boolean',
            'option_d_figure_on_top' => 'boolean',
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
