<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Chapter;
use App\Models\CreativeQuestion;
use App\Models\McqQuestion;
use App\Models\ShortQuestion;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuestionBankController extends Controller
{
    /**
     * Display paginated and searchable Short Questions.
     */
    public function shortIndex(Request $request): View
    {
        $query = ShortQuestion::with(['academicClass', 'subject', 'chapter'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->integer('class_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->integer('subject_id'));
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->integer('chapter_id'));
        }

        return view('admin.questions.short', [
            'questions' => $query->paginate(10)->withQueryString(),
            'classes' => AcademicClass::orderBy('id')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created Short Question.
     */
    public function shortStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'question' => ['required', 'string'],
            'mark' => ['required', 'integer', 'min:1', 'max:100'],
            'answer' => ['required', 'string'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'figure_on_top' => ['nullable', 'boolean'],
        ]);

        ShortQuestion::create([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'question' => $validated['question'],
            'mark' => $validated['mark'],
            'answer' => $validated['answer'],
            'figure' => $this->uploadFigure($request->file('figure_file')),
            'figure_on_top' => $request->boolean('figure_on_top'),
        ]);

        return redirect()
            ->route('admin.questions.short.index')
            ->with('status', 'সংক্ষিপ্ত প্রশ্ন সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update an existing Short Question.
     */
    public function shortUpdate(Request $request, ShortQuestion $shortQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'question' => ['required', 'string'],
            'mark' => ['required', 'integer', 'min:1', 'max:100'],
            'answer' => ['required', 'string'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'figure_on_top' => ['nullable', 'boolean'],
        ]);

        $figure = $this->resolveUpdatedFigure(
            $request,
            'figure_file',
            'remove_figure',
            $shortQuestion->figure
        );

        $shortQuestion->update([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'question' => $validated['question'],
            'mark' => $validated['mark'],
            'answer' => $validated['answer'],
            'figure' => $figure,
            'figure_on_top' => $request->boolean('figure_on_top'),
        ]);

        return redirect()
            ->route('admin.questions.short.index')
            ->with('status', 'সংক্ষিপ্ত প্রশ্ন সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete a Short Question and its figure.
     */
    public function shortDestroy(ShortQuestion $shortQuestion): RedirectResponse
    {
        $this->deletePhysicalFigure($shortQuestion->figure);
        $shortQuestion->delete();

        return redirect()
            ->route('admin.questions.short.index')
            ->with('status', 'সংক্ষিপ্ত প্রশ্নটি মুছে ফেলা হয়েছে।');
    }

    /**
     * Display paginated and searchable MCQ Questions.
     */
    public function mcqIndex(Request $request): View
    {
        $query = McqQuestion::with(['academicClass', 'subject', 'chapter'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('option_a', 'like', "%{$search}%")
                    ->orWhere('option_b', 'like', "%{$search}%")
                    ->orWhere('option_c', 'like', "%{$search}%")
                    ->orWhere('option_d', 'like', "%{$search}%")
                    ->orWhere('short_explanation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->integer('class_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->integer('subject_id'));
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->integer('chapter_id'));
        }

        return view('admin.questions.mcq', [
            'questions' => $query->paginate(10)->withQueryString(),
            'classes' => AcademicClass::orderBy('id')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created MCQ Question (default mark is 1).
     */
    public function mcqStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'title' => ['required', 'string'],
            'mark' => ['nullable', 'integer', 'min:1', 'max:100'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_a_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_b_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_c_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_d' => ['required', 'string', 'max:255'],
            'option_d_figure_file' => ['nullable', 'image', 'max:4096'],
            'answer_option' => ['required', 'in:a,b,c,d'],
            'short_explanation' => ['nullable', 'string'],
        ]);

        McqQuestion::create([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'mark' => (int) ($validated['mark'] ?? 1),
            'figure' => $this->uploadFigure($request->file('figure_file')),
            'figure_on_top' => $request->boolean('figure_on_top'),
            'option_a' => $validated['option_a'],
            'option_a_figure' => $this->uploadFigure($request->file('option_a_figure_file')),
            'option_a_figure_on_top' => $request->boolean('option_a_figure_on_top'),
            'option_b' => $validated['option_b'],
            'option_b_figure' => $this->uploadFigure($request->file('option_b_figure_file')),
            'option_b_figure_on_top' => $request->boolean('option_b_figure_on_top'),
            'option_c' => $validated['option_c'],
            'option_c_figure' => $this->uploadFigure($request->file('option_c_figure_file')),
            'option_c_figure_on_top' => $request->boolean('option_c_figure_on_top'),
            'option_d' => $validated['option_d'],
            'option_d_figure' => $this->uploadFigure($request->file('option_d_figure_file')),
            'option_d_figure_on_top' => $request->boolean('option_d_figure_on_top'),
            'answer_option' => $validated['answer_option'],
            'short_explanation' => $validated['short_explanation'] ?? null,
        ]);

        return redirect()
            ->route('admin.questions.mcq.index')
            ->with('status', 'বহুনির্বাচনি প্রশ্ন (MCQ) সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update an existing MCQ Question.
     */
    public function mcqUpdate(Request $request, McqQuestion $mcqQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'title' => ['required', 'string'],
            'mark' => ['nullable', 'integer', 'min:1', 'max:100'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_a_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_b_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_c_figure_file' => ['nullable', 'image', 'max:4096'],
            'option_d' => ['required', 'string', 'max:255'],
            'option_d_figure_file' => ['nullable', 'image', 'max:4096'],
            'answer_option' => ['required', 'in:a,b,c,d'],
            'short_explanation' => ['nullable', 'string'],
        ]);

        $mcqQuestion->update([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'mark' => (int) ($validated['mark'] ?? 1),
            'figure' => $this->resolveUpdatedFigure($request, 'figure_file', 'remove_figure', $mcqQuestion->figure),
            'figure_on_top' => $request->boolean('figure_on_top'),
            'option_a' => $validated['option_a'],
            'option_a_figure' => $this->resolveUpdatedFigure($request, 'option_a_figure_file', 'remove_option_a_figure', $mcqQuestion->option_a_figure),
            'option_a_figure_on_top' => $request->boolean('option_a_figure_on_top'),
            'option_b' => $validated['option_b'],
            'option_b_figure' => $this->resolveUpdatedFigure($request, 'option_b_figure_file', 'remove_option_b_figure', $mcqQuestion->option_b_figure),
            'option_b_figure_on_top' => $request->boolean('option_b_figure_on_top'),
            'option_c' => $validated['option_c'],
            'option_c_figure' => $this->resolveUpdatedFigure($request, 'option_c_figure_file', 'remove_option_c_figure', $mcqQuestion->option_c_figure),
            'option_c_figure_on_top' => $request->boolean('option_c_figure_on_top'),
            'option_d' => $validated['option_d'],
            'option_d_figure' => $this->resolveUpdatedFigure($request, 'option_d_figure_file', 'remove_option_d_figure', $mcqQuestion->option_d_figure),
            'option_d_figure_on_top' => $request->boolean('option_d_figure_on_top'),
            'answer_option' => $validated['answer_option'],
            'short_explanation' => $validated['short_explanation'] ?? null,
        ]);

        return redirect()
            ->route('admin.questions.mcq.index')
            ->with('status', 'বহুনির্বাচনি প্রশ্ন (MCQ) সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete an MCQ Question and all its figures.
     */
    public function mcqDestroy(McqQuestion $mcqQuestion): RedirectResponse
    {
        $this->deletePhysicalFigure($mcqQuestion->figure);
        $this->deletePhysicalFigure($mcqQuestion->option_a_figure);
        $this->deletePhysicalFigure($mcqQuestion->option_b_figure);
        $this->deletePhysicalFigure($mcqQuestion->option_c_figure);
        $this->deletePhysicalFigure($mcqQuestion->option_d_figure);

        $mcqQuestion->delete();

        return redirect()
            ->route('admin.questions.mcq.index')
            ->with('status', 'বহুনির্বাচনি প্রশ্নটি মুছে ফেলা হয়েছে।');
    }

    /**
     * Display paginated and searchable Creative Questions.
     */
    public function creativeIndex(Request $request): View
    {
        $query = CreativeQuestion::with(['academicClass', 'subject', 'chapter'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('question_a', 'like', "%{$search}%")
                    ->orWhere('question_b', 'like', "%{$search}%")
                    ->orWhere('question_c', 'like', "%{$search}%")
                    ->orWhere('question_d', 'like', "%{$search}%")
                    ->orWhere('answer_explanation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->integer('class_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->integer('subject_id'));
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->integer('chapter_id'));
        }

        return view('admin.questions.creative', [
            'questions' => $query->paginate(10)->withQueryString(),
            'classes' => AcademicClass::orderBy('id')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'chapters' => Chapter::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created Creative Question.
     */
    public function creativeStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'title' => ['required', 'string'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'question_a' => ['required', 'string'],
            'mark_a' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_a_file' => ['nullable', 'image', 'max:4096'],
            'question_b' => ['required', 'string'],
            'mark_b' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_b_file' => ['nullable', 'image', 'max:4096'],
            'question_c' => ['required', 'string'],
            'mark_c' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_c_file' => ['nullable', 'image', 'max:4096'],
            'question_d' => ['required', 'string'],
            'mark_d' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_d_file' => ['nullable', 'image', 'max:4096'],
            'answer_explanation' => ['nullable', 'string'],
        ]);

        $fullMark = (int) $validated['mark_a'] + (int) $validated['mark_b'] + (int) $validated['mark_c'] + (int) $validated['mark_d'];

        CreativeQuestion::create([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'figure' => $this->uploadFigure($request->file('figure_file')),
            'figure_on_top' => $request->boolean('figure_on_top'),
            'question_a' => $validated['question_a'],
            'mark_a' => $validated['mark_a'],
            'figure_a' => $this->uploadFigure($request->file('figure_a_file')),
            'figure_a_on_top' => $request->boolean('figure_a_on_top'),
            'question_b' => $validated['question_b'],
            'mark_b' => $validated['mark_b'],
            'figure_b' => $this->uploadFigure($request->file('figure_b_file')),
            'figure_b_on_top' => $request->boolean('figure_b_on_top'),
            'question_c' => $validated['question_c'],
            'mark_c' => $validated['mark_c'],
            'figure_c' => $this->uploadFigure($request->file('figure_c_file')),
            'figure_c_on_top' => $request->boolean('figure_c_on_top'),
            'question_d' => $validated['question_d'],
            'mark_d' => $validated['mark_d'],
            'figure_d' => $this->uploadFigure($request->file('figure_d_file')),
            'figure_d_on_top' => $request->boolean('figure_d_on_top'),
            'full_mark' => $fullMark,
            'answer_explanation' => $validated['answer_explanation'] ?? null,
        ]);

        return redirect()
            ->route('admin.questions.creative.index')
            ->with('status', 'সৃজনশীল প্রশ্ন সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update an existing Creative Question.
     */
    public function creativeUpdate(Request $request, CreativeQuestion $creativeQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')->where('class_id', $request->input('class_id'))],
            'chapter_id' => ['required', 'integer', Rule::exists('chapters', 'id')->where('subject_id', $request->input('subject_id'))],
            'title' => ['required', 'string'],
            'figure_file' => ['nullable', 'image', 'max:4096'],
            'question_a' => ['required', 'string'],
            'mark_a' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_a_file' => ['nullable', 'image', 'max:4096'],
            'question_b' => ['required', 'string'],
            'mark_b' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_b_file' => ['nullable', 'image', 'max:4096'],
            'question_c' => ['required', 'string'],
            'mark_c' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_c_file' => ['nullable', 'image', 'max:4096'],
            'question_d' => ['required', 'string'],
            'mark_d' => ['required', 'integer', 'min:1', 'max:50'],
            'figure_d_file' => ['nullable', 'image', 'max:4096'],
            'answer_explanation' => ['nullable', 'string'],
        ]);

        $fullMark = (int) $validated['mark_a'] + (int) $validated['mark_b'] + (int) $validated['mark_c'] + (int) $validated['mark_d'];

        $creativeQuestion->update([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'figure' => $this->resolveUpdatedFigure($request, 'figure_file', 'remove_figure', $creativeQuestion->figure),
            'figure_on_top' => $request->boolean('figure_on_top'),
            'question_a' => $validated['question_a'],
            'mark_a' => $validated['mark_a'],
            'figure_a' => $this->resolveUpdatedFigure($request, 'figure_a_file', 'remove_figure_a', $creativeQuestion->figure_a),
            'figure_a_on_top' => $request->boolean('figure_a_on_top'),
            'question_b' => $validated['question_b'],
            'mark_b' => $validated['mark_b'],
            'figure_b' => $this->resolveUpdatedFigure($request, 'figure_b_file', 'remove_figure_b', $creativeQuestion->figure_b),
            'figure_b_on_top' => $request->boolean('figure_b_on_top'),
            'question_c' => $validated['question_c'],
            'mark_c' => $validated['mark_c'],
            'figure_c' => $this->resolveUpdatedFigure($request, 'figure_c_file', 'remove_figure_c', $creativeQuestion->figure_c),
            'figure_c_on_top' => $request->boolean('figure_c_on_top'),
            'question_d' => $validated['question_d'],
            'mark_d' => $validated['mark_d'],
            'figure_d' => $this->resolveUpdatedFigure($request, 'figure_d_file', 'remove_figure_d', $creativeQuestion->figure_d),
            'figure_d_on_top' => $request->boolean('figure_d_on_top'),
            'full_mark' => $fullMark,
            'answer_explanation' => $validated['answer_explanation'] ?? null,
        ]);

        return redirect()
            ->route('admin.questions.creative.index')
            ->with('status', 'সৃজনশীল প্রশ্ন সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete a Creative Question and all its figures.
     */
    public function creativeDestroy(CreativeQuestion $creativeQuestion): RedirectResponse
    {
        $this->deletePhysicalFigure($creativeQuestion->figure);
        $this->deletePhysicalFigure($creativeQuestion->figure_a);
        $this->deletePhysicalFigure($creativeQuestion->figure_b);
        $this->deletePhysicalFigure($creativeQuestion->figure_c);
        $this->deletePhysicalFigure($creativeQuestion->figure_d);

        $creativeQuestion->delete();

        return redirect()
            ->route('admin.questions.creative.index')
            ->with('status', 'সৃজনশীল প্রশ্নটি মুছে ফেলা হয়েছে।');
    }

    /**
     * Delete a specific figure image from any question type.
     */
    public function destroyFigure(string $type, int $id, string $field): RedirectResponse
    {
        $allowedFields = [
            'short' => ['figure'],
            'mcq' => ['figure', 'option_a_figure', 'option_b_figure', 'option_c_figure', 'option_d_figure'],
            'creative' => ['figure', 'figure_a', 'figure_b', 'figure_c', 'figure_d'],
        ];

        if (! isset($allowedFields[$type]) || ! in_array($field, $allowedFields[$type], true)) {
            abort(404);
        }

        $model = match ($type) {
            'short' => ShortQuestion::findOrFail($id),
            'mcq' => McqQuestion::findOrFail($id),
            'creative' => CreativeQuestion::findOrFail($id),
        };

        $this->deletePhysicalFigure($model->{$field});
        $model->update([$field => null]);

        return back()->with('status', 'চিত্রটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Resolve figure path during update: remove if requested, replace if new file uploaded, or keep existing.
     */
    protected function resolveUpdatedFigure(Request $request, string $fileKey, string $removeKey, ?string $currentFigure): ?string
    {
        if ($request->hasFile($fileKey)) {
            $this->deletePhysicalFigure($currentFigure);

            return $this->uploadFigure($request->file($fileKey));
        }

        if ($request->boolean($removeKey)) {
            $this->deletePhysicalFigure($currentFigure);

            return null;
        }

        return $currentFigure;
    }

    /**
     * Upload an optional figure file into public/uploads/figures and return its relative path.
     */
    protected function uploadFigure(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        $directory = public_path('uploads/figures');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/uploads/figures/'.$filename;
    }

    /**
     * Delete a physical figure file from public/uploads/figures if it exists.
     */
    protected function deletePhysicalFigure(?string $relativePath): void
    {
        if (! $relativePath || ! str_starts_with($relativePath, '/uploads/figures/')) {
            return;
        }

        $fullPath = public_path(ltrim($relativePath, '/'));
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
