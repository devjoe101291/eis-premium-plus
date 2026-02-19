<?php

namespace App\Mail;

use App\Models\ExamResult;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentPassedExamMail extends Mailable
{
    use Queueable, SerializesModels;

    public $examResult;

    public function __construct(ExamResult $examResult)
    {
        $this->examResult = $examResult;
    }

    public function build()
    {
        // Safely resolve student name parts
        $firstName = $this->examResult->user->details->first_name ?? '';
        $lastName = $this->examResult->user->details->last_name ?? '';
        $studentName = htmlspecialchars(trim($firstName . ' ' . $lastName) ?: 'Unknown Student');

        // Exam title may be stored as `title` on the Exam model
        $examTitleRaw = $this->examResult->exam->title ?? $this->examResult->exam->exam_title ?? 'Unknown Exam';
        $examTitle = htmlspecialchars($examTitleRaw);

        $score = htmlspecialchars($this->examResult->score ?? '0');
        $totalPoints = htmlspecialchars($this->examResult->total_points ?? '0');
        $attempt = htmlspecialchars($this->examResult->attempt_number ?? '1');

        // taken_at may be a DateTime or string; guard parsing
        try {
            $takenAt = $this->examResult->taken_at ? \Carbon\Carbon::parse($this->examResult->taken_at)->format('F d, Y h:i A') : now()->format('F d, Y h:i A');
        } catch (\Exception $e) {
            $takenAt = is_string($this->examResult->taken_at) ? htmlspecialchars($this->examResult->taken_at) : now()->format('F d, Y h:i A');
        }

        $takenAt = htmlspecialchars($takenAt);

        // Get admin's first name (user_type = 0 for admin)
        $adminUser = \App\Models\User::where('user_type', 0)
            ->with('details')
            ->first();
        
        $adminFirstName = 'Admin'; // Default fallback
        if ($adminUser && $adminUser->details) {
            $adminFirstName = htmlspecialchars($adminUser->details->first_name ?? 'Admin');
        }

        return $this->subject('Student Passed Exam: ' . $studentName)
                    ->html($this->renderTemplate($studentName, $examTitle, $score, $totalPoints, $attempt, $takenAt, $adminFirstName));
    }

    private function renderTemplate($studentName, $examTitle, $score, $totalPoints, $attempt, $takenAt, $adminFirstName)
    {
        // Get current year and lesson info
        $year = date('Y');
        $lessonName = $this->examResult->exam->topic->lesson->lesson_name ?? 'Unknown Lesson';
        
        // Calculate percentage
        $percentage = $totalPoints > 0 ? round(($score / $totalPoints) * 100) : 0;
        
        // Get total number of attempts for this exam by this student
        $totalAttempts = \App\Models\ExamResult::where('user_id', $this->examResult->user_id)
            ->where('fk_exam_id', $this->examResult->fk_exam_id)
            ->count();

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Passed Exam</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f6f8;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            border-left: 6px solid transparent;
            background-clip: padding-box;
            position: relative;
        }
        /* Gradient side accent */
        .email-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #005D84, #2C8D9A);
        }
        .email-header {
            background: linear-gradient(135deg, #005D84, #2C8D9A);
            padding: 28px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .email-body {
            padding: 36px 40px;
            line-height: 1.7;
        }
        .email-body h2 {
            color: #005D84;
            font-size: 18px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .email-body p {
            margin: 12px 0;
            font-size: 15px;
            color: #444;
        }
        .highlight {
            color: #2C8D9A;
            font-weight: 600;
        }
        .success-highlight {
            color: #10b981;
            font-weight: 600;
        }
        .details-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 18px 0;
            border: 1px solid #e5e9ec;
        }
        .details-box p {
            margin: 8px 0;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .email-footer {
            background: #f9fafb;
            padding: 18px 16px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>FATASHHEALTHCARE</h1>
        </div>

        <div class="email-body">
            <h2>Hello {$adminFirstName},</h2>
            <p>Great news! A student has successfully <span class="success-highlight">passed</span> their exam.</p>
            <p>Below are the exam completion details:</p>
            
            <div class="details-box">
                <p><strong>Student Name:</strong> {$studentName}</p>
                <p><strong>Exam Title:</strong> {$examTitle}</p>
                <p><strong>Program:</strong> {$lessonName}</p>
                <p><strong>Score:</strong> {$score} / {$totalPoints} points ({$percentage}%)</p>
                <p><strong>Current Attempt:</strong> Attempt #{$attempt}</p>
                <p><strong>Total Attempts:</strong> {$totalAttempts} attempt(s)</p>
                <p><strong>Date Taken:</strong> {$takenAt}</p>
                <p><strong>Status:</strong> <span class="success-highlight">Passed</span></p>
            </div>
            
            <div class="divider"></div>
            
            <p>You can review the detailed exam results and generate a certificate for this student through the admin portal.</p>
            <p>Best regards,</p>
            <p><strong>FATASHHEALTHCARE</strong></p>
        </div>

        <div class="email-footer">
            &copy; {$year} FATASHHEALTHCARE. All rights reserved.<br>
            This is an automated notification - please do not reply.<br>
        </div>
    </div>
</body>
</html>
HTML;
    }
}