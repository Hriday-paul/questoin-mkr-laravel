<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['class_id', 'name'])]
class Subject extends Model
{
    /**
     * Get the class that owns the subject.
     *
     * @return BelongsTo<AcademicClass, $this>
     */
    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Get the chapters for this subject.
     *
     * @return HasMany<Chapter, $this>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'subject_id');
    }

    /**
     * Get the short questions for this subject.
     *
     * @return HasMany<ShortQuestion, $this>
     */
    public function shortQuestions(): HasMany
    {
        return $this->hasMany(ShortQuestion::class, 'subject_id');
    }

    /**
     * Get the MCQ questions for this subject.
     *
     * @return HasMany<McqQuestion, $this>
     */
    public function mcqQuestions(): HasMany
    {
        return $this->hasMany(McqQuestion::class, 'subject_id');
    }

    /**
     * Get the creative questions for this subject.
     *
     * @return HasMany<CreativeQuestion, $this>
     */
    public function creativeQuestions(): HasMany
    {
        return $this->hasMany(CreativeQuestion::class, 'subject_id');
    }
}
