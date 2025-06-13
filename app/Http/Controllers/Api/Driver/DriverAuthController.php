<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverVerification;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DriverAuthController extends Controller
{
   public function submitSignupDetails(Request $request, TwilioService $twilio)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:drivers,email',
            'phone_no' => 'required|digits:10|unique:drivers,phone_no',
            'alternative_no' => 'nullable|digits:10',
            'dob' => 'nullable|date',

            // Vehicle details
            'vehicle_brand' => 'nullable|string',
            'vehicle_plate_no' => 'nullable|string',
            'vehicle_capicty' => 'nullable|string',
            'vehicle_production_year' => 'nullable|string',
            'commercial_permit_year' => 'nullable|string',

            // File uploads
            'profile' => 'nullable|image',
            'vehicle_photo' => 'nullable|image',
            'vehicle_rc_front_photo' => 'nullable|image',
            'vehicle_rc_back_photo' => 'nullable|image',

            // Verification details
            'licence_no' => 'nullable|string',
            'licence_expire_date' => 'nullable|date',
            'aadhar_no' => 'nullable|string',

            // Verification files
            'licence_front_photo' => 'nullable|image',
            'licence_back_photo' => 'nullable|image',
            'selfie_with_driver_licence' => 'nullable|image',
            'aadhar_front_photo' => 'nullable|image',
            'aadhar_back_photo' => 'nullable|image',
            'police_verification_certificate' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Upload files
        $data = $request->all();
        $uploadFields = [
            'profile',
            'vehicle_photo',
            'vehicle_rc_front_photo',
            'vehicle_rc_back_photo',
            'licence_front_photo',
            'licence_back_photo',
            'selfie_with_driver_licence',
            'aadhar_front_photo',
            'aadhar_back_photo',
            'police_verification_certificate',
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("drivers/{$field}", 'public');
            }
        }
        $otp = rand(1000, 9999);

        // Create driver
        $driver = Driver::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'] ?? null,
            'email' => $data['email'] ?? null,
            'phone_no' => $data['phone_no'],
            'alternative_no' => $data['alternative_no'] ?? null,
            'dob' => $data['dob'] ?? null,
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'vehicle_brand' => $data['vehicle_brand'] ?? null,
            'vehicle_plate_no' => $data['vehicle_plate_no'] ?? null,
            'vehicle_capicty' => $data['vehicle_capicty'] ?? null,
            'vehicle_production_year' => $data['vehicle_production_year'] ?? null,
            'commercial_permit_year' => $data['commercial_permit_year'] ?? null,
            'profile' => $data['profile'] ?? null,
            'vehicle_photo' => $data['vehicle_photo'] ?? null,
            'vehicle_rc_front_photo' => $data['vehicle_rc_front_photo'] ?? null,
            'vehicle_rc_back_photo' => $data['vehicle_rc_back_photo'] ?? null,
        ]);

        // Create verification
        $verification = DriverVerification::create([
            'driver_id' => $driver->id,
            'licence_no' => $data['licence_no'] ?? null,
            'licence_expire_date' => $data['licence_expire_date'] ?? null,
            'licence_front_photo' => $data['licence_front_photo'] ?? null,
            'licence_back_photo' => $data['licence_back_photo'] ?? null,
            'selfie_with_driver_licence' => $data['selfie_with_driver_licence'] ?? null,
            'aadhar_no' => $data['aadhar_no'] ?? null,
            'aadhar_front_photo' => $data['aadhar_front_photo'] ?? null,
            'aadhar_back_photo' => $data['aadhar_back_photo'] ?? null,
            'police_verification_certificate' => $data['police_verification_certificate'] ?? null,
        ]);
        try {
            Log::info("OTP for {$driver->phone_no} is $otp");
            $twilio->sendSms("+91" . $driver->phone_no, "Your OTP is: $otp");
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send SMS', 'error' => $e->getMessage()], 500);
        }
        return response()->json([
            'status' => true,
            'message' => 'Signup initiated. OTP sent to phone.'
            // 'message' => 'Driver and verification created successfully.',
            // 'driver' => $driver->load('verification')
        ]);
    }
}
