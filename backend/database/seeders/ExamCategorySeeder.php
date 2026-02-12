<?php

namespace Database\Seeders;

use App\Models\ExamCategory;
use Illuminate\Database\Seeder;

class ExamCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Knowledge',
                'description' => 'General knowledge and common topics',
            ],
            [
                'name' => 'Technical Skills',
                'description' => 'Technical and professional skills assessment',
            ],
            [
                'name' => 'Safety Training',
                'description' => 'Workplace safety and compliance training',
            ],
            [
                'name' => 'Certification',
                'description' => 'Professional certification exams',
            ],
        ];

        foreach ($categories as $category) {
            ExamCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
