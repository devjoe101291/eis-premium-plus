<?php

namespace App\Mail;

use App\Models\User;

class RegistrationPending
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $fullName = trim(
            ($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? '')
        );
        $fullName = $fullName ?: ($this->user->name ?? 'Valued User');
        $fullName = htmlspecialchars($fullName);

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval</title>
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
        .email-footer a {
            color: #005D84;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>FATASHHEALTHCARE</h1>
        </div>

        <div class="email-body">
            <h2>Hello {$fullName},</h2>
            <p>Thank you for registering with <strong>FATASHHEALTHCARE</strong>.</p>
            <p>Your account is currently <span class="highlight">pending approval</span> by our admin team.</p>
            <p>Once approved, you'll receive another email notification letting you know that you can now log in to the system.</p>
            <div class="divider"></div>
            <p>We appreciate your patience and look forward to having you onboard soon.</p>
            <p>Best regards,</p>
            <p><strong>The FATASHHEALTHCARE Team</strong></p>
        </div>

        <div class="email-footer">
            &copy; 2025 FATASHHEALTHCARE. All rights reserved.<br>
            This is an automated message - please do not reply.<br>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
