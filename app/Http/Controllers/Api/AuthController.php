<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function submitSignupDetails(Request $request, TwilioService $twilio)
    {
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|string|max:255',
            'lastname'      => 'nullable|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone_no'      => 'required|digits:10|unique:users,phone_no',
            'alternative_no'=> 'nullable|digits:10',
            'dob'           => 'nullable',
            'gender'        => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $otp = rand(1000, 9999);

        $user = User::create([
            'firstname'      => $request->firstname,
            'lastname'       => $request->lastname,
            'email'          => $request->email,
            'phone_no'       => $request->phone_no,
            'alternative_no' => $request->alternative_no,
            'dob'            => $request->dob,
            'gender'         => $request->gender,
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'status'         => 0,
        ]);

        try {
            Log::info("OTP for {$user->phone_no} is $otp");
            $twilio->sendSms("+91" . $user->phone_no, "Your OTP is: $otp");
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send SMS', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Signup initiated. OTP sent to phone.'
        ]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required|digits:10|exists:users,phone_no',
            'otp'          => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_no', $request->phone_no)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP.'
            ], 401);
        }

        $user->status = 1;
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'OTP verified successfully.',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    // Resend OTP
    public function resendOtp(Request $request, TwilioService $twilio)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required|digits:10|exists:users,phone_no',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_no', $request->phone_no)->first();

        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        try {
            Log::info("Resent OTP for {$user->phone_no} is $otp");
            $twilio->sendSms("+91" . $user->phone_no, "Your new OTP is: $otp");
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to resend OTP', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'OTP resent successfully.'
        ]);
    }
    public function login(Request $request, TwilioService $twilio)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required|digits:10|exists:users,phone_no',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        $user = User::where('phone_no', $request->phone_no)->first();

        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        try {
            Log::info("OTP for {$user->phone_no} is $otp");
            $twilio->sendSms("+91" . $user->phone_no, "Your OTP is: $otp");
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send SMS', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'OTP sent to phone for login.'
        ]);
    }
}
