<?php

namespace App\Mail;

require_once app_path('Helpers/email_curl.php');

class PasswordChanged
{
    public static function send($user)
    {
        $subject = 'Password Changed - Secure Your Account';
        $firstName = htmlspecialchars($user->details->first_name ?? $user->username);
        $email = htmlspecialchars($user->email);
        $year = date('Y');
        $changeTime = date('M d, Y \a\t H:i');

        // --- Style Definitions ---
        $primaryStart = '#5B21B6';
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
        $warningBoxStyle = "background-color: #fee2e2; border-radius: 8px; padding: 16px; margin: 24px 0; border: 1px solid #fecaca; border-left: 4px solid #ef4444;";
        $detailsBoxStyle = "background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 24px 0; border: 1px solid #e5e7eb;";
        $detailsPStyle = "margin: 0 0 8px;";
        $checklistStyle = "background-color: #f0fdf4; border-radius: 8px; padding: 20px; margin: 24px 0; border: 1px solid #dcfce7;";
        $checklistItemStyle = "margin: 0 0 12px; {$fontStack}";
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
            <h1 style="{$headerH1Style}">Password Changed</h1>
        </div>
        
        <!-- Body Content -->
        <div style="{$contentStyle}">
            <p style="{$pStyle}">Hello <strong style="{$strongStyle}">$firstName</strong>,</p>
            <p style="{$pStyle}">This is a notification that your password has been <strong style="{$strongStyle}">successfully changed</strong> on your  Premium Plus Employee-in-Service System account.</p>

            <!-- Warning Box -->
            <div style="{$warningBoxStyle}">
                <p style="margin: 0; {$fontStack}"><strong style="color: #7f1d1d;">Security Alert:</strong> If you did not change your password, your account may be compromised. Please reset your password immediately or contact our support team.</p>
            </div>

            <!-- Account Details -->
            <div style="{$detailsBoxStyle}">
                <p style="{$detailsPStyle}"><strong style="{$strongStyle}">Account Email:</strong> $email</p>
                <p style="margin: 0; {$fontStack}"><strong style="{$strongStyle}">Change Time:</strong> $changeTime</p>
            </div>

            <p style="{$pStyle}">You can now log in with your new password</p>
        </div>
        
        <!-- Footer -->
        <div style="{$footerStyle}">
            <p style="{$footerPStyle}">&copy; $year  Premium Plus Employee-in-Service System. All rights reserved.</p>
            <p style="{$footerPStyle}">If you have any questions or concerns, please contact our support team immediately.</p>
        </div>
    </div>
</body>
</html>
HTML;

        // Build email params
        $emailParams = [
            'to'           => $email,
            'from'         => 'no-reply@proweaver.com',
            'from_name'    => ' Premium Plus Employee-in-Service System',
            'subject'      => $subject,
            'body'         => $htmlMessage,
            'comp_name'    => 'Proweaver',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
        ];

        // Send the email
        \send_email($emailParams, 'Password change notification sent', 'Failed to send password change notification');
    }
}
