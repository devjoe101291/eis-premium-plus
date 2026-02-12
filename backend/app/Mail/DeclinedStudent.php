<?php

namespace App\Mail;

require_once app_path('Helpers/email_curl.php');

class DeclinedStudent
{
    public static function send($user)
    {
        $subject = 'Your FATASHHEALTHCARE Account Has Been Declined';
        $firstName = htmlspecialchars($user->details->first_name ?? $user->username);
        $companyName = 'FATASHHEALTHCARE';
        $year = date('Y');

        // --- Style Definitions ---
        $alertColor = '#dc2626'; // Red for declined status
        $primaryStart = '#005D84'; // For links

        $fontStack = "font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;";
        $bodyStyle = "margin: 0; padding: 0; width: 100% !important; -webkit-font-smoothing: antialiased; background-color: #f3f4f6; {$fontStack}";
        $wrapperStyle = "width: 90%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;";

        // Use alert color for the header
        $headerStyle = "padding: 28px 30px; background: {$alertColor}; color: #ffffff; text-align: center;";
        $headerH1Style = "margin: 0; font-size: 26px; font-weight: 600;";

        $contentStyle = "padding: 30px 30px; font-size: 16px; line-height: 1.6; color: #374151;";
        $pStyle = "margin: 0 0 16px;";
        $strongStyle = "color: #111827;";
        $alertStrongStyle = "color: {$alertColor}; font-weight: 600;";
        $linkStyle = "color: {$primaryStart}; text-decoration: none; font-weight: 500;";

        $footerStyle = "background-color: #f9fafb; color: #6b7280; text-align: center; padding: 20px 30px; font-size: 13px; border-top: 1px solid #e5e7eb;";
        $footerPStyle = "margin: 0 0 4px;";
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
            <h1 style="{$headerH1Style}">Account Update</h1>
        </div>
        
        <!-- Body Content -->
        <div style="{$contentStyle}">
            <p style="{$pStyle}">Hello <strong style="{$strongStyle}">{$firstName}</strong>,</p>
            <p style="{$pStyle}">We regret to inform you that your account registration request has been <strong style="{$alertStrongStyle}">declined</strong> after review.</p>
            <p style="{$pStyle}">This may be due to missing, incomplete, or unverifiable information provided during registration.</p>
            <p style="{$pStyle}">If you believe this was a mistake or would like to reapply, please contact our support team for further assistance:</p>
            
            <p style="{$pStyle} text-align: center; margin-top: 24px; margin-bottom: 24px;">
                <a href="mailto:info@fatashhealthcarellc.com" style="{$linkStyle}">info@fatashhealthcarellc.com</a>
            </p>

            <p style="{$pStyle}">Thank you for your interest in joining <strong style="{$strongStyle}">{$companyName}</strong>.</p>
        </div>
        
        <!-- Footer -->
        <div style="{$footerStyle}">
            <p style="{$footerPStyle}">© {$year} {$companyName}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;

        $emailParams = [
            'to'           => $user->email,
            'from'         => 'no-reply@fatashhealthcare.com',
            'from_name'    => 'FATASHHEALTHCARE',
            'subject'      => $subject,
            'body'         => $htmlMessage,
            'comp_name'    => 'FATASHHEALTHCARE',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
        ];

        \send_email($emailParams, 'Decline email sent', 'Failed to send decline email');
    }
}
