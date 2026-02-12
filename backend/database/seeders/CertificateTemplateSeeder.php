<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = [
            'template_name' => 'Default Certificate Template',
            'content' => json_encode([
                'title' => 'Certificate of Completion',
                'body' => 'This is to certify that {{user_name}} has successfully completed the {{exam_title}} examination with a score of {{score}}% on {{date}}.',
                'footer' => 'This certificate is issued by the Examination Management System.',
            ]),
            'e_signature' => 'System Administrator',
            'is_active' => true,
        ];

        CertificateTemplate::firstOrCreate(
            ['template_name' => $template['template_name']],
            $template
        );
    }
}
