<?php

namespace App\Mail;

require_once app_path('Helpers/email_curl.php');

class ApprovedStudent
{
    public static function send($user)
    {
        $subject = 'Your FATASHHEALTHCARE Account Has Been Approved';
        $studentType = $user->user_type == 1 ? 'Full-Time' : 'Part-Time';
        $firstName = htmlspecialchars($user->details->first_name ?? $user->username);
        $email = htmlspecialchars($user->email);
        $lessonName = $user->lesson?->lesson_name ?? null;
        $year = date('Y');

        // --- Style Definitions ---
        // Use your requested gradient colors
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
        $detailsPStyle = "margin: 0 0 8px;";
        $buttonContainerStyle = "text-align: center; margin: 24px 0 12px;";
        // Button uses the gradient with a solid fallback, just as you requested
        $buttonStyle = "display: inline-block; background-color: {$primaryStart}; background-image: {$gradient}; color: #ffffff; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;";
        $footerStyle = "background-color: #f9fafb; color: #6b7280; text-align: center; padding: 20px 30px; font-size: 13px; border-top: 1px solid #e5e7eb;";
        $footerPStyle = "margin: 0 0 4px;";
        $footerLinkStyle = "color: {$primaryStart}; text-decoration: none;";
        // --- End Style Definitions ---

        $htmlMessage = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$subject</title>
</head>
<body style="{$bodyStyle}">
    <div style="{$wrapperStyle}">
        <!-- Header -->
        <div style="{$headerStyle}">
            <h1 style="{$headerH1Style}">Account Approved!</h1>
        </div>
        
        <!-- Body Content -->
        <div style="{$contentStyle}">
            <p style="{$pStyle}">Hello <strong style="{$strongStyle}">$firstName</strong>,</p>
            <p style="{$pStyle}">We are excited to let you know that your account has been <strong style="{$strongStyle}">approved</strong> by the administrator!</p>
            <p style="{$pStyle}">You are now registered as a <strong style="color: {$primaryStart};">{$studentType}</strong> student.</p>

            <!-- Login Details Box -->
            <div style="{$detailsBoxStyle}">
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Email:</strong> $email</p>
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Password:</strong> The same password you used during registration</p>
HTML;

        // Add lesson information if assigned
        if ($lessonName) {
            $htmlMessage .= <<<HTML
                <p style="margin: 0; {$fontStack}"><strong style="{$strongStyle}">Lesson:</strong> <span style="color: {$primaryStart};">{$lessonName}</span></p>
HTML;
        } else {
            $htmlMessage .= <<<HTML
                <p style="margin: 0; {$fontStack}"><strong style="{$strongStyle}">Lesson:</strong> Not assigned yet</p>
HTML;
        }

        $htmlMessage .= <<<HTML
            </div>

            <p style="{$pStyle}">You can now log in to access your student dashboard:</p>
            
            <!-- CTA Button -->
            <div style="{$buttonContainerStyle}">
                <a href="https://fatashhealthcare1.com/portal/" style="{$buttonStyle}">
                    Go to Student Portal
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="{$footerStyle}">
            <p style="{$footerPStyle}">© $year FATASHHEALTHCARE. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;

        // Build email params
        $emailParams = [
            'to'           => $user->email,
            'from'         => 'no-reply@fatashhealthcare.com',
            'from_name'    => 'FATASHHEALTHCARE',
            'subject'      => $subject,
            'body'         => $htmlMessage,
            'comp_name'    => 'FATASHHEALTHCARE',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
        ];

        // Use the same helper as registration
        \send_email($emailParams, 'Approved email sent', 'Failed to send approved email');
    }
}
