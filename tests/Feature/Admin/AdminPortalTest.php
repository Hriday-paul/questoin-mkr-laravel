<?php

use App\Models\AcademicClass;
use App\Models\Chapter;
use App\Models\CreativeQuestion;
use App\Models\McqQuestion;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;

test('admin portal renders sidebar with QuestionMKR logo, nested question links, and navbar photo popover', function () {
    $admin = User::factory()->admin()->create([
        'organization_name' => 'QuestionMKR HQ',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Question')
        ->assertSee('MKR')
        ->assertSee(route('admin.classes.index'), escape: false)
        ->assertSee(route('admin.subjects.index'), escape: false)
        ->assertSee(route('admin.chapters.index'), escape: false)
        ->assertSee(route('admin.questions.short.index'), escape: false)
        ->assertSee(route('admin.questions.mcq.index'), escape: false)
        ->assertSee(route('admin.questions.creative.index'), escape: false)
        ->assertSee(route('admin.users.index'), escape: false)
        ->assertSee(route('admin.organization'), escape: false)
        ->assertSee('আমার প্রতিষ্ঠান')
        ->assertSee('লগআউট');
});

test('admin can create and search short, mcq, and creative questions', function () {
    $admin = User::factory()->admin()->create();
    $academicClass = AcademicClass::create(['name' => '৯ম-১০ম শ্রেণি']);
    $subject = Subject::create(['name' => 'পদার্থবিজ্ঞান', 'class_id' => $academicClass->id]);
    $chapter = Chapter::create(['name' => '২য় অধ্যায়: গতি', 'class_id' => $academicClass->id, 'subject_id' => $subject->id]);

    // 1. Short Question
    $this->actingAs($admin)->post(route('admin.questions.short.store'), [
        'class_id' => $academicClass->id,
        'subject_id' => $subject->id,
        'chapter_id' => $chapter->id,
        'question' => 'ত্বরণ কাকে বলে?',
        'mark' => 2,
        'answer' => 'সময়ের সাথে বেগের পরিবর্তনের হারকে ত্বরণ বলে।',
        'figure_on_top' => true,
    ])->assertRedirect(route('admin.questions.short.index'));

    expect(ShortQuestion::where('question', 'ত্বরণ কাকে বলে?')->first()?->figure_on_top)->toBeTrue();

    // 2. MCQ Question
    $this->actingAs($admin)->post(route('admin.questions.mcq.store'), [
        'class_id' => $academicClass->id,
        'subject_id' => $subject->id,
        'chapter_id' => $chapter->id,
        'title' => 'নিচের কোনটি ভেক্টর রাশি?',
        'mark' => 1,
        'option_a' => 'দ্রুতি',
        'option_b' => 'কাজ',
        'option_c' => 'সরণ',
        'option_d' => 'তাপমাত্রা',
        'answer_option' => 'c',
        'short_explanation' => 'সরণের মান ও দিক উভয়ই আছে।',
        'figure_on_top' => true,
    ])->assertRedirect(route('admin.questions.mcq.index'));

    expect(McqQuestion::where('title', 'নিচের কোনটি ভেক্টর রাশি?')->first()?->answer_option)->toBe('c');

    // 3. Creative Question
    $this->actingAs($admin)->post(route('admin.questions.creative.store'), [
        'class_id' => $academicClass->id,
        'subject_id' => $subject->id,
        'chapter_id' => $chapter->id,
        'title' => 'একটি গাড়ি স্থির অবস্থান থেকে যাত্রা শুরু করে।',
        'question_a' => 'জড়তা কী?',
        'mark_a' => 1,
        'question_b' => 'ঘর্ষণ কেন প্রয়োজন?',
        'mark_b' => 2,
        'question_c' => 'গাড়িটির ত্বরণ নির্ণয় কর।',
        'mark_c' => 3,
        'question_d' => 'যাত্রাপথের বেগ-সময় লেখচিত্র বিশ্লেষণ কর।',
        'mark_d' => 4,
        'answer_explanation' => 'সম্পূর্ণ সমাধান।',
        'figure_on_top' => true,
    ])->assertRedirect(route('admin.questions.creative.index'));

    expect(CreativeQuestion::first()?->full_mark)->toBe(10);

    // Search & View pages
    $this->actingAs($admin)->get(route('admin.questions.short.index', ['search' => 'ত্বরণ']))
        ->assertOk()
        ->assertSee('ত্বরণ কাকে বলে?');

    $this->actingAs($admin)->get(route('admin.questions.mcq.index', ['search' => 'ভেক্টর']))
        ->assertOk()
        ->assertSee('নিচের কোনটি ভেক্টর রাশি?');

    $this->actingAs($admin)->get(route('admin.questions.creative.index', ['search' => 'গাড়ি']))
        ->assertOk()
        ->assertSee('একটি গাড়ি স্থির অবস্থান থেকে যাত্রা শুরু করে।');
});
