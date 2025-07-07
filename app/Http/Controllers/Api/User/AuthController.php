<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
            'firstName'     => 'required|string|max:255',
            'lastName'      => 'nullable|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'      => 'required|digits:10|unique:users,phone_no',
            'alternativeNumber'=> 'nullable|digits:10',
            'dob'           => 'required',
            'gender'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $otp = rand(1000, 9999);

        $user = User::create([
            'firstname'      => $request->firstName,
            'lastname'       => $request->lastName,
            'email'          => $request->email,
            'phone_no'       => $request->phone,
            'alternative_no' => $request->alternativeNumber,
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
            'phone' => 'required|digits:10|exists:users,phone_no',
            'otp'          => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_no', $request->phone)
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
            'user'    => new UserResource($user),
        ]);
    }

    // Resend OTP
    public function resendOtp(Request $request, TwilioService $twilio)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10|exists:users,phone_no',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_no', $request->phone)->first();

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
            'phone' => 'required|digits:10|exists:users,phone_no',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        $user = User::where('phone_no', $request->phone)->first();

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

   public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not authenticated or token missing.'
        ], 401);
    }

    public function userProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully.',
            'user' => new UserResource($user)
        ]);
    }
}
