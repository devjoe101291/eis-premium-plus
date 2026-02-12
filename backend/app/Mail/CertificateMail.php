<?php

namespace App\Mail;

require_once app_path('Helpers/email_curl.php');

class CertificateMail
{
    public static function send($examResult, $certificatePath)
    {
        $subject = 'Certificate of Completion';
        $firstName = htmlspecialchars($examResult->user->details->first_name ?? $examResult->user->username);
        $email = htmlspecialchars($examResult->user->email);
        $examTitle = htmlspecialchars($examResult->exam->title ?? 'Exam');
        $lessonName = $examResult->exam->topic->lesson->lesson_name ?? 'Unknown Lesson';
        $year = date('Y');
        
        // Generate a friendly filename for the attachment
        $studentLastName = htmlspecialchars($examResult->user->details->last_name ?? 'Student');
        $sanitizedFirstName = preg_replace('/[^a-zA-Z0-9]/', '', $firstName);
        $sanitizedLastName = preg_replace('/[^a-zA-Z0-9]/', '', $studentLastName);
        $certificateFileName = "Certificate_{$sanitizedFirstName}_{$sanitizedLastName}_" . date('Y') . ".pdf";
        
        // Get dynamic portal URL based on APP_URL environment variable
        $appUrl = config('app.url', 'http://localhost');
        
        // Determine frontend URL based on environment
        if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            // Local development - use localhost with port 5173 (Vite default)
            $portalUrl = 'http://localhost:8080';
        } else {
            // Production - use the actual domain
            $portalUrl = 'https://fatashhealthcare1.com/portal/';
        }
        
        // Use the certificate file directly (already PDF from frontend)
        $pdfPath = $certificatePath;

        // --- Style Definitions ---
        $primaryStart = '#005D84';
        $primaryEnd = '#71D8BD';
        $gradient = "linear-gradient(135deg, $primaryStart 0%, $primaryEnd 100%)";
        
        $fontStack = "font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;";
        $bodyStyle = "margin: 0; padding: 0; width: 100% !important; -webkit-font-smoothing: antialiased; background-color: #f3f4f6; {$fontStack}";
        $wrapperStyle = "width: 90%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;";
        $headerStyle = "padding: 28px 30px; background: {$gradient}; color: #ffffff; text-align: center;";
        $headerH1Style = "margin: 0; font-size: 26px; font-weight: 600;";
        $contentStyle = "padding: 30px 30px; font-size: 16px; line-height: 1.6; color: #374151;";
        $pStyle = "margin: 0 0 16px;";
        $strongStyle = "color: #111827;";
        $detailsBoxStyle = "background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 24px 0; border: 1px solid #e5e7eb;";
        $attachmentBoxStyle = "background-color: #eff6ff; border-radius: 8px; padding: 24px; margin: 24px 0; border: 2px dashed {$primaryStart}; text-align: center;";
        $detailsPStyle = "margin: 0 0 8px;";
        $buttonContainerStyle = "text-align: center; margin: 24px 0 12px;";
        $buttonStyle = "display: inline-block; background-color: {$primaryStart}; background-image: {$gradient}; color: #ffffff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;";
        $footerStyle = "background-color: #f9fafb; color: #6b7280; text-align: center; padding: 20px 30px; font-size: 13px; border-top: 1px solid #e5e7eb;";
        $footerPStyle = "margin: 0 0 4px;";
        // --- End Style Definitions ---

        $htmlMessage = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>$subject</title>
</head>
<body style="{$bodyStyle}">
    <div style="{$wrapperStyle}">
        <!-- Header -->
        <div style="{$headerStyle}">
            <h1 style="{$headerH1Style}">Congratulations!</h1>
        </div>
        
        <!-- Body Content -->
        <div style="{$contentStyle}">
            <p style="{$pStyle}">Hello <strong style="{$strongStyle}">$firstName</strong>,</p>
            <p style="{$pStyle}">Congratulations on successfully completing your exam!</p>
            <p style="{$pStyle}">We are pleased to inform you that your <strong style="color: {$primaryStart};">Certificate of Completion</strong> is now ready.</p>

            <!-- Exam Details Box -->
            <div style="{$detailsBoxStyle}">
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Exam:</strong> {$examTitle}</p>
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Program:</strong> {$lessonName}</p>
                <p style="margin: 0; {$fontStack}"><strong style="{$strongStyle}">Status:</strong> <span style="color: #10b981;">Passed</span></p>
            </div>

            <!-- Certificate Attachment Box -->
            <div style="{$attachmentBoxStyle}">
                <p style="margin: 0 0 12px; font-size: 18px; font-weight: 600; color: {$primaryStart};"> Your Certificate is Attached</p>
                <p style="margin: 0 0 8px; font-size: 14px; color: #374151;">Look for <strong>Certificate_{$sanitizedFirstName}_{$sanitizedLastName}_{$year}.pdf</strong> in the attachment section below</p>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Click on the attachment to download and save your certificate</p>
            </div>
            
            <p style="{$pStyle}; font-size: 14px;">Keep your certificate safe for your records. You may need it for future employment or educational opportunities.</p>

            <p style="{$pStyle}; font-size: 14px; color: #6b7280;">Thank you for your dedication and hard work. We wish you continued success in your healthcare career!</p>
            
            <p style="{$pStyle}; font-size: 12px; color: #9ca3af; margin-top: 20px;">If you have any questions or need assistance, please contact us at info@fatashhealthcarellc.com</p>
        </div>
        
        <!-- Footer -->
        <div style="{$footerStyle}">
            <p style="{$footerPStyle}">© $year FATASHHEALTHCARE. All rights reserved.</p>
            <p style="{$footerPStyle}; font-size: 11px; color: #9ca3af;">This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
HTML;

        // Create plain text version for better spam score
        $plainTextMessage = "Hello $firstName,\n\n";
        $plainTextMessage .= "Congratulations on successfully completing your exam!\n\n";
        $plainTextMessage .= "We are pleased to inform you that your Certificate of Completion is now ready.\n\n";
        $plainTextMessage .= "Exam Details:\n";
        $plainTextMessage .= "- Exam: $examTitle\n";
        $plainTextMessage .= "- Program: $lessonName\n";
        $plainTextMessage .= "- Status: Passed\n\n";
        $plainTextMessage .= "Your certificate PDF is attached to this email. Please check the attachment section of this email and download it to save your certificate.\n\n";
        $plainTextMessage .= "Keep your certificate safe for your records. You may need it for future employment or educational opportunities.\n\n";
        $plainTextMessage .= "Thank you for your dedication and hard work. We wish you continued success in your healthcare career!\n\n";
        $plainTextMessage .= "If you have any questions, please contact us at support@fatashhealthcare.com\n\n";
        $plainTextMessage .= "&copy; $year FATASHHEALTHCARE. All rights reserved.\n";
        $plainTextMessage .= "This is an automated email. Please do not reply to this message.";
        
        // Build email params with attachment
        $emailParams = [
            'to'           => $examResult->user->email,
            'from'         => 'no-reply@fatashhealthcare.com',
            'from_name'    => 'FATASHHEALTHCARE',
            'subject'      => $subject,
            'body'         => $htmlMessage,
            'text_body'    => $plainTextMessage, // Add plain text version
            'comp_name'    => 'FATASHHEALTHCARE',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
            'attachment'   => $pdfPath, // Add certificate PDF attachment
            'reply_to'     => 'support@fatashhealthcare.com', // Add reply-to address
            'attachment_filename' => $certificateFileName, // Custom filename for attachment
        ];

        // Log the email sending attempt
        \Log::info('Sending certificate email', [
            'to' => $examResult->user->email,
            'student' => $firstName,
            'exam' => $examTitle,
            'certificate_path' => $pdfPath,
            'certificate_filename' => $certificateFileName,
            'file_exists' => file_exists($pdfPath),
            'file_size' => file_exists($pdfPath) ? filesize($pdfPath) : 0,
        ]);

        // Send email with attachment
        $result = \send_email($emailParams, 'Certificate email sent', 'Failed to send certificate email');
        
        // Log the result
        \Log::info('Email send result', ['result' => $result]);
        
        return $result;
    }
}
