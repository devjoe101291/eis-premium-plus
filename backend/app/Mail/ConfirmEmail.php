<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $user;
  public $otp;

  public function __construct($user, $otp)
  {
    $this->user = $user;
    $this->otp = $otp;
  }

public function build()
{
    // ✅ Access the related user's first name
    $userName = $this->user->details->first_name ?? $this->user->name ?? 'User';
    $companyName = 'Premium Plus Employee-in-Service System';
    $otp = $this->otp;

    $htmlContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f7; margin:0; padding:20px;">
  <table align="center" width="600" style="background:#fff; border-radius:8px; overflow:hidden;">
    <tr>
      <td style="background:#ff8e48; color:#fff; text-align:center; padding:20px;">
        <h1>Password Reset Request</h1>
      </td>
    </tr>
    <tr>
      <td style="padding:20px; color:#333;">
        <p>Hi <strong>{$userName}</strong>,</p>
        <p>We received a request to reset your password for your <strong>{$companyName}</strong> account.</p>
        <p style="text-align:center; font-size:24px; font-weight:bold; margin:30px 0;">{$otp}</p>
        <p>This OTP is valid for 5 minutes. Do not share it with anyone.</p>
        <p>If you did not request a password reset, please ignore this email.</p>
      </td>
    </tr>
    <tr>
      <td style="background:#f9f9f9; text-align:center; padding:15px; font-size:12px; color:#888;">
        &copy; 2025 {$companyName}. All rights reserved.
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

    return $this->subject('Password Reset OTP')
        ->html($htmlContent);
}

}