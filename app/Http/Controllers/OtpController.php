<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    /**
     * Send Phone OTP — now delivered via Email (Mailtrap) instead of SMS.
     * This allows any email address (including fake/test) to receive the OTP.
     * When you go live with real users, swap this back to Twilio SMS.
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'email' => 'nullable|email', // we need the email to send OTP to
        ]);

        // Normalize phone number
        $phone = preg_replace('/[^\d+]/', '', $request->phone);
        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . $phone;
        }

        // Use the email from the request or fall back to session
        $emailTarget = $request->email
            ?? $request->session()->get('otp_registration_email')
            ?? null;

        $otpCode = rand(100000, 999999);

        // Store OTP in DB indexed by phone number
        OtpVerification::updateOrCreate(
            ['phone' => $phone],
            [
                'otp_code'    => $otpCode,
                'expires_at'  => now()->addMinutes(10),
                'verified_at' => null,
            ]
        );

        // Send via Mailtrap email instead of SMS
        if ($emailTarget) {
            try {
                Mail::raw(
                    "Your LifeLink phone verification code is: {$otpCode}\n\nThis code expires in 10 minutes.\n\n(This OTP is for phone number: {$phone})",
                    function ($message) use ($emailTarget, $phone) {
                        $message->to($emailTarget)
                            ->subject("LifeLink – Phone OTP: {$phone}");
                    }
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mailtrap OTP Error: ' . $e->getMessage());
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to send OTP email. Check Mailtrap configuration.',
                ], 500);
            }
        } else {
            // Fallback: just log it if no email is available
            \Illuminate\Support\Facades\Log::info("Phone OTP for {$phone} is: {$otpCode}");
        }

        return response()->json(['status' => 'success', 'message' => 'OTP sent to your email inbox.']);
    }

    /**
     * Verify Phone OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'otp_code' => 'required|string',
        ]);

        $phone = preg_replace('/[^\d+]/', '', $request->phone);
        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . $phone;
        }

        $otpRecord = OtpVerification::where('phone', $phone)
            ->where('otp_code', (int) $request->otp_code)
            ->where('expires_at', '>', now())
            ->first();

        if ($otpRecord) {
            $otpRecord->update(['verified_at' => now()]);

            if (auth()->check()) {
                $user = auth()->user();
                $user->phone_verified_at = now();
                $user->save();
            } else {
                session(['verified_phone' => $phone]);
            }

            return response()->json(['status' => 'success', 'message' => 'Phone verified successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
    }

    /**
     * Send Email OTP — delivered via Mailtrap
     */
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $otpCode = rand(100000, 999999);

        Cache::put('email_otp_' . $email, $otpCode, now()->addMinutes(10));

        try {
            Mail::raw(
                "Your LifeLink email verification code is: {$otpCode}\n\nThis code expires in 10 minutes.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('LifeLink – Verify Your Email');
                }
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mailtrap Email OTP Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send OTP email. Check Mailtrap configuration.',
            ], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Email OTP sent successfully.']);
    }

    /**
     * Verify Email OTP
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'otp_code' => 'required|string',
        ]);

        $cachedOtp = Cache::get('email_otp_' . $request->email);

        if ($cachedOtp && (string) $cachedOtp === (string) $request->otp_code) {
            Cache::forget('email_otp_' . $request->email);

            if (auth()->check()) {
                $user = auth()->user();
                $user->email_verified_at = now();
                $user->save();
            } else {
                session(['verified_email' => $request->email]);
            }

            return response()->json(['status' => 'success', 'message' => 'Email verified successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
    }
}
