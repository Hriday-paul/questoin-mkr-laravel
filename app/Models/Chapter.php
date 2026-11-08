<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['class_id', 'subject_id', 'name'])]
class Chapter extends Model
{
    /**
     * Get the class that owns the chapter.
     *
     * @return BelongsTo<AcademicClass, $this>
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Get the subject that owns the chapter.
     *
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the short questions for this chapter.
     *
     * @return HasMany<ShortQuestion, $this>
     */
    public function shortQuestions(): HasMany
    {
        return $this->hasMany(ShortQuestion::class, 'chapter_id');
    }

    /**
     * Get the MCQ questions for this chapter.
     *
     * @return HasMany<McqQuestion, $this>
     */
    public function mcqQuestions(): HasMany
    {
        return $this->hasMany(McqQuestion::class, 'chapter_id');
    }

    /**
     * Get the creative questions for this chapter.
     *
     * @return HasMany<CreativeQuestion, $this>
     */
    public function creativeQuestions(): HasMany
    {
        return $this->hasMany(CreativeQuestion::class, 'chapter_id');
    }
}
