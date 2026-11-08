<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('classes')]
#[Fillable(['name'])]
class AcademicClass extends Model
{
    /**
     * Get the subjects for this class.
     *
     * @return HasMany<Subject, $this>
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    /**
     * Get the chapters for this class.
     *
     * @return HasMany<Chapter, $this>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'class_id');
    }

    /**
     * Get the short questions for this class.
     *
     * @return HasMany<ShortQuestion, $this>
     */
    public function shortQuestions(): HasMany
    {
        return $this->hasMany(ShortQuestion::class, 'class_id');
    }

    /**
     * Get the MCQ questions for this class.
     *
     * @return HasMany<McqQuestion, $this>
     */
    public function mcqQuestions(): HasMany
    {
        return $this->hasMany(McqQuestion::class, 'class_id');
    }

    /**
     * Get the creative questions for this class.
     *
     * @return HasMany<CreativeQuestion, $this>
     */
    public function creativeQuestions(): HasMany
    {
        return $this->hasMany(CreativeQuestion::class, 'class_id');
    }
}
