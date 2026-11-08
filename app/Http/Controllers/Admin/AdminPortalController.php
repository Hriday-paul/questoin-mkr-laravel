<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreChapterRequest;
use App\Http\Requests\Admin\StoreClassRequest;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateChapterRequest;
use App\Http\Requests\Admin\UpdateClassRequest;
use App\Http\Requests\Admin\UpdateOrganizationRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\AcademicClass;
use App\Models\Chapter;
use App\Models\CreativeQuestion;
use App\Models\McqQuestion;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPortalController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        $totalUsers = User::where('role', UserRole::User)->count();
        $recentUsers = User::where('role', UserRole::User)
            ->latest()
            ->take(5)
            ->get();

        $shortCount = ShortQuestion::count();
        $mcqCount = McqQuestion::count();
        $creativeCount = CreativeQuestion::count();

        return view('admin.dashboard', [
            'totalClasses' => AcademicClass::count(),
            'totalSubjects' => Subject::count(),
            'totalChapters' => Chapter::count(),
            'totalQuestions' => $shortCount + $mcqCount + $creativeCount,
            'shortCount' => $shortCount,
            'mcqCount' => $mcqCount,
            'creativeCount' => $creativeCount,
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * Display the classes management page.
     */
    public function classes(): View
    {
        $classes = AcademicClass::latest()->get();

        return view('admin.classes.index', [
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created class in storage.
     */
    public function storeClass(StoreClassRequest $request): RedirectResponse
    {
        AcademicClass::create($request->validated());

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'নতুন শ্রেণি সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update the specified class in storage.
     */
    public function updateClass(UpdateClassRequest $request, AcademicClass $academicClass): RedirectResponse
    {
        $academicClass->update($request->validated());

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'শ্রেণির নাম সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroyClass(AcademicClass $academicClass): RedirectResponse
    {
        $academicClass->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'শ্রেণিটি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Display the subjects management page with filter and pagination.
     */
    public function subjects(Request $request): View
    {
        $query = Subject::with('academicClass')->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->integer('class_id'));
        }

        $classes = AcademicClass::orderBy('id')->get();

        return view('admin.subjects.index', [
            'subjects' => $query->paginate(10)->withQueryString(),
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created subject in storage.
     */
    public function storeSubject(StoreSubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'নতুন বিষয় সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update the specified subject in storage.
     */
    public function updateSubject(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'বিষয়ের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroySubject(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'বিষয়টি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Display the chapters management page with filter and pagination.
     */
    public function chapters(Request $request): View
    {
        $query = Chapter::with(['academicClass', 'subject'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->integer('class_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->integer('subject_id'));
        }

        $classes = AcademicClass::orderBy('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.chapters.index', [
            'chapters' => $query->paginate(10)->withQueryString(),
            'classes' => $classes,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created chapter in storage.
     */
    public function storeChapter(StoreChapterRequest $request): RedirectResponse
    {
        Chapter::create($request->validated());

        return redirect()
            ->route('admin.chapters.index')
            ->with('status', 'নতুন অধ্যায় সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Update the specified chapter in storage.
     */
    public function updateChapter(UpdateChapterRequest $request, Chapter $chapter): RedirectResponse
    {
        $chapter->update($request->validated());

        return redirect()
            ->route('admin.chapters.index')
            ->with('status', 'অধ্যায়ের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified chapter from storage.
     */
    public function destroyChapter(Chapter $chapter): RedirectResponse
    {
        $chapter->delete();

        return redirect()
            ->route('admin.chapters.index')
            ->with('status', 'অধ্যায়টি সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Display the registered users page with search & pagination.
     */
    public function users(Request $request): View
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('organization_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->string('role')->toString());
        }

        return view('admin.users.index', [
            'users' => $query->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Manually verify a user's email address.
     */
    public function verifyUser(User $user): RedirectResponse
    {
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return back()->with('status', 'ব্যবহারকারীর ইমেইল সফলভাবে যাচাইকৃত হিসেবে চিহ্নিত করা হয়েছে।');
    }

    /**
     * Delete a user account.
     */
    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return back()->with('status', 'আপনি নিজের অ্যাডমিন অ্যাকাউন্ট মুছে ফেলতে পারবেন না।');
        }

        $user->delete();

        return back()->with('status', 'ব্যবহারকারীর অ্যাকাউন্ট সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * Display the admin's organization profile page.
     */
    public function organization(): View
    {
        return view('admin.organization');
    }

    /**
     * Update the admin's organization details.
     */
    public function updateOrganization(UpdateOrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'name' => $validated['organization_name'],
            'organization_name' => $validated['organization_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
        ]);

        return back()->with('status', 'আপনার প্রতিষ্ঠানের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }
}
