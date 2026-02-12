<?php

namespace App\Mail;

require_once app_path('Helpers/email_curl.php');

class EmailChanged
{
    public static function send($user, $oldEmail)
    {
        $subject = 'Email Address Changed - Action Required';
        $firstName = htmlspecialchars($user->details->first_name ?? $user->username);
        $newEmail = htmlspecialchars($user->email);
        $oldEmailDisplay = htmlspecialchars($oldEmail);
        $year = date('Y');

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
        $warningBoxStyle = "background-color: #fef3c7; border-radius: 8px; padding: 16px; margin: 24px 0; border: 1px solid #fcd34d; border-left: 4px solid #f59e0b;";
        $detailsBoxStyle = "background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 24px 0; border: 1px solid #e5e7eb;";
        $detailsPStyle = "margin: 0 0 8px;";
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
            <h1 style="{$headerH1Style}">Email Address Changed</h1>
        </div>
        
        <!-- Body Content -->
        <div style="{$contentStyle}">
            <p style="{$pStyle}">Hello <strong style="{$strongStyle}">$firstName</strong>,</p>
            <p style="{$pStyle}">This is a notification that your email address has been <strong style="{$strongStyle}">successfully changed</strong> on your FATASHHEALTHCARE account.</p>

            <!-- Warning Box -->
            <div style="{$warningBoxStyle}">
                <p style="margin: 0; {$fontStack}"><strong style="color: #92400e;">Important:</strong> If you did not make this change, please contact our support team immediately.</p>
            </div>

            <!-- Details Box -->
            <div style="{$detailsBoxStyle}">
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Previous Email:</strong> $oldEmailDisplay</p>
                <p style="margin: 0; {$fontStack}"><strong style="{$strongStyle}">New Email:</strong> <span style="color: {$primaryStart};">$newEmail</span></p>
            </div>

            <p style="{$pStyle}">You can now log in using your new email address:</p>
        </div>
        
        <!-- Footer -->
        <div style="{$footerStyle}">
            <p style="{$footerPStyle}">&copy; $year FATASHHEALTHCARE. All rights reserved.</p>
            <p style="{$footerPStyle}">If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
HTML;

        // Build email params
        $emailParams = [
            'to'           => $newEmail,
            'from'         => 'no-reply@fatashhealthcare.com',
            'from_name'    => 'FATASHHEALTHCARE',
            'subject'      => $subject,
            'body'         => $htmlMessage,
            'comp_name'    => 'FATASHHEALTHCARE',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
        ];

        // Send the email
        \send_email($emailParams, 'Email change notification sent', 'Failed to send email change notification');
    }
}
