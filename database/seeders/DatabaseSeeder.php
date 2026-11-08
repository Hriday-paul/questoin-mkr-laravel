<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AcademicClass;
use App\Models\Chapter;
use App\Models\CreativeQuestion;
use App\Models\McqQuestion;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'QuestionMKR HQ',
                'organization_name' => 'QuestionMKR HQ',
                'phone_number' => '+8801700000000',
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'আদর্শ বিদ্যাপীঠ',
                'organization_name' => 'আদর্শ বিদ্যাপীঠ',
                'phone_number' => '+8801800000000',
                'role' => UserRole::User,
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );

        $class6 = AcademicClass::firstOrCreate(['name' => '৬ষ্ঠ শ্রেণি']);
        $class7 = AcademicClass::firstOrCreate(['name' => '৭ম শ্রেণি']);
        $class8 = AcademicClass::firstOrCreate(['name' => '৮ম শ্রেণি']);
        $class910 = AcademicClass::firstOrCreate(['name' => '৯ম-১০ম শ্রেণি (এসএসসি)']);
        $class1112 = AcademicClass::firstOrCreate(['name' => 'একাদশ-দ্বাদশ শ্রেণি (এইচএসসি)']);

        $bangla = Subject::firstOrCreate(['name' => 'বাংলা ১ম পত্র', 'class_id' => $class910->id]);
        $math = Subject::firstOrCreate(['name' => 'সাধারণ গণিত', 'class_id' => $class910->id]);
        $physics = Subject::firstOrCreate(['name' => 'পদার্থবিজ্ঞান', 'class_id' => $class910->id]);
        $ict = Subject::firstOrCreate(['name' => 'তথ্য ও যোগাযোগ প্রযুক্তি (ICT)', 'class_id' => $class1112->id]);

        $chPhysics1 = Chapter::firstOrCreate([
            'name' => '১ম অধ্যায়: ভৌত রাশি এবং পরিমাপ',
            'class_id' => $class910->id,
            'subject_id' => $physics->id,
        ]);

        $chPhysics2 = Chapter::firstOrCreate([
            'name' => '২য় অধ্যায়: গতি',
            'class_id' => $class910->id,
            'subject_id' => $physics->id,
        ]);

        $chMath1 = Chapter::firstOrCreate([
            'name' => '১ম অধ্যায়: বাস্তব সংখ্যা',
            'class_id' => $class910->id,
            'subject_id' => $math->id,
        ]);

        $chIct4 = Chapter::firstOrCreate([
            'name' => '৪র্থ অধ্যায়: ওয়েব ডিজাইন পরিচিতি এবং HTML',
            'class_id' => $class1112->id,
            'subject_id' => $ict->id,
        ]);

        ShortQuestion::firstOrCreate(
            ['question' => 'মাত্রা সমীকরণ কাকে বলে?', 'chapter_id' => $chPhysics1->id],
            [
                'class_id' => $class910->id,
                'subject_id' => $physics->id,
                'mark' => 2,
                'answer' => 'কোনো ভৌত রাশিতে উপস্থিত মৌলিক রাশিগুলোর ঘাত বা শক্তির সূচক নির্দেশক সমীকরণকে মাত্রা সমীকরণ বলে।',
                'figure' => null,
                'figure_on_top' => false,
            ]
        );

        McqQuestion::firstOrCreate(
            ['title' => 'নিচের কোনটি মৌলিক রাশি নয়?', 'chapter_id' => $chPhysics1->id],
            [
                'class_id' => $class910->id,
                'subject_id' => $physics->id,
                'mark' => 1,
                'option_a' => 'দৈর্ঘ্য',
                'option_b' => 'ভর',
                'option_c' => 'বল',
                'option_d' => 'সময়',
                'answer_option' => 'c',
                'short_explanation' => 'বল একটি লব্ধ রাশি কারণ বল = ভর × ত্বরণ।',
                'figure_on_top' => false,
            ]
        );

        CreativeQuestion::firstOrCreate(
            ['title' => 'একটি গাড়ি স্থির অবস্থান থেকে যাত্রা শুরু করে ১০ সেকেন্ডে ২০ মিটার/সেকেন্ড বেগ প্রাপ্ত হলো।', 'chapter_id' => $chPhysics2->id],
            [
                'class_id' => $class910->id,
                'subject_id' => $physics->id,
                'figure_on_top' => true,
                'question_a' => 'ত্বরণ কাকে বলে?',
                'mark_a' => 1,
                'question_b' => 'সুষম বেগে চলমান বস্তুর ত্বরণ শূন্য কেন? ব্যাখ্যা কর।',
                'mark_b' => 2,
                'question_c' => 'উদ্দীপকের গাড়িটির ত্বরণ নির্ণয় কর।',
                'mark_c' => 3,
                'question_d' => 'গাড়িটি যদি পরবর্তী ১০ সেকেন্ড সমবেগে চলে, তবে মোট অতিক্রান্ত দূরত্ব বিশ্লেষণ কর।',
                'mark_d' => 4,
                'full_mark' => 10,
                'answer_explanation' => 'ক) সময়ের সাথে বেগের পরিবর্তনের হারকে ত্বরণ বলে। গ) ত্বরণ a = (v - u)/t = 20/10 = 2 m/s²।',
            ]
        );
    }
}
