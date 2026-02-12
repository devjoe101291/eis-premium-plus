<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Carbon;
use App\Mail\ConfirmEmail;

require_once app_path('Helpers/email_curl.php');
require_once app_path('Mail/ConfirmEmail.php');

class PasswordResetController extends Controller
{



    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:eis_users,email'
        ]);

        $otp = rand(100000, 999999);
        Cache::put('password_reset_' . $request->email, $otp, now()->addMinutes(5));

        //     $emailBody = <<<HTML
        // ...your OTP email template with $otp...
        // HTML;



        $user = User::where('email', $request->email)->first();
        $emailBody = (new ConfirmEmail($user, $otp))->render();
        Mail::to($request->email)->send(new ConfirmEmail($user, $otp));
        $params = [
            'from' => 'spproweaver@gmail.com',
            'from_name' => 'Premium Plus Employee-in-Service',
            'to' => trim($request->email),
            'subject' => 'Password Reset OTP',
            'body' => $emailBody,
            'comp_name' => 'Premium Plus Employee-in-Service',
            'companyToken' => '67a74306b06d0c01624fe0d0249a570f4d093747',
        ];

        // $params['debug'] = true;
        $response = send_email($params);
        // dd($response);



        return response()->json([
            'message' => 'OTP sent to your email',
            'debug' => $response
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $cachedOtp = Cache::get('password_reset_' . $request->email);

        Log::info('OTP Verify', [
            'email' => $request->email,
            'otp_input' => $request->otp,
            'cached_otp' => $cachedOtp
        ]);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 422);
        }

        // OTP verified, delete it to prevent reuse
        Cache::forget('password_reset_' . $request->email);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully'
        ]);
    }


    // Step 2: Reset Password - verify OTP and change password
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:eis_users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // 🧠 Check if new password is same as old one
    if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Your new password cannot be the same as your old password.'
        ], 422);
    }

    // ✅ Save new password
    $user->password = bcrypt($request->password);
    $user->save();

    Cache::forget('password_reset_' . $request->email);

    return response()->json(['message' => 'Password reset successfully']);
}

}
