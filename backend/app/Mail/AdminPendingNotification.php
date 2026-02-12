<?php

namespace App\Mail;

use App\Models\User;

class AdminPendingNotification
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
        $fullName = $fullName ?: ($this->user->name ?? 'New User');
        $fullName = htmlspecialchars($fullName);

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New User Pending Approval</title>
<style>
    body {
        background: #f3f6f8;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .email-wrapper {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        position: relative;
    }
    .email-wrapper::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, #005D84, #2C8D9A);
    }
    .header {
        background: linear-gradient(135deg, #005D84, #2C8D9A);
        color: #fff;
        text-align: center;
        padding: 25px 20px;
    }
    .header h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }
    .content {
        padding: 30px 40px;
        line-height: 1.6;
    }
    .content p {
        font-size: 15px;
        margin: 12px 0;
    }
    .highlight {
        color: #2C8D9A;
        font-weight: 600;
    }
    .footer {
        text-align: center;
        padding: 16px;
        font-size: 13px;
        color: #777;
        border-top: 1px solid #eee;
        background: #f9fafb;
    }
</style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>FATASHHEALTHCARE</h1>
        </div>
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new user has just registered on the system and is currently <span class="highlight">pending approval</span>.</p>
            <p><strong>Registrant Details:</strong></p>
            <p>Name: {$fullName}<br>
               Email: {$this->user->email}</p>
            <p>Please log in to the Admin Panel to review and approve their registration.</p>
            <p>Best regards,<br><strong>FATASHHEALTHCARE System</strong></p>
        </div>
        <div class="footer">
            &copy; 2025 FATASHHEALTHCARE. All rights reserved.<br>
            Automated message - please do not reply.
        </div>
    </div>
</body>
</html>
HTML;
    }
}
