<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Resources\DriverResource;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverVerification;
use App\Repositories\DriverRepository;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class DriverAuthController extends Controller
{
   public function submitSignupDetails(Request $request)
    {
        $vehicleType = $request->input('vehicleType');
        $validator = Validator::make($request->all(), [
            'vehicleType' => ['required', 'in:1,2,3'],
            'firstName' => 'required|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required|digits:10|unique:drivers,phone_no',
            'profile' => 'required|image',
            'alternativeNumber' => 'nullable|digits:10',
            'dob' => 'required|date',

            // Vehicle details
            'vehicleBrand' => 'required|string',
            'vehiclePlateNumber' => 'required|string',
            'vehicleCapicty' => 'nullable|string',
            'vehicleProductionYear' => 'required|string',
            'commercialPermitYear' => 'nullable|string',

            'vehicleModel' => [Rule::requiredIf($vehicleType == 2), 'string', 'max:255'],
            'vehicleColor' => [Rule::requiredIf($vehicleType == 2), 'string', 'max:255'],
            'vehiclePermitPhotoA' => [Rule::requiredIf($vehicleType == 2), 'image'],
            'vehiclePermitPhotoB' => [Rule::requiredIf($vehicleType == 2), 'image'],
            'vehicleSideRegistrationPhoto' => [Rule::requiredIf($vehicleType == 2), 'image'],

            // File uploads
            'vehiclePhoto' => 'required|image',
            'vehicleRegistrationCertificateFrontPhoto' => 'required|image',
            'vehicleRegistrationCertificateBackPhoto' => 'required|image',

            // Verification details
            'licenceNumber' => 'required|string',
            'licenceExpireDate' => 'required',
            'licenceFrontPhoto' => 'required|image',
            'licenceBackPhoto' => 'required|image',
            'selfieWithDriverLicence' => 'required|image',
            'aadharNumber' => 'required|string',
            'aadharFrontPhoto' => 'required|image',
            'aadharBackPhoto' => 'required|image',
            'policeVerificationCertificate' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Upload files
        $data = $request->all();
        $uploadFields = [
            'profile',
            'vehiclePhoto',
            'vehicleRegistrationCertificateFrontPhoto',
            'vehicleRegistrationCertificateBackPhoto',
            'licenceFrontPhoto',
            'licenceBackPhoto',
            'selfieWithDriverLicence',
            'aadharFrontPhoto',
            'aadharBackPhoto',
            'policeVerificationCertificate',
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("drivers/{$field}", 'public');
            }
        }

        // Create driver
        $driver = Driver::create([
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'] ?? null,
            'email' => $data['email'] ?? null,
            'phone_no' => $data['phone'],
            'alternative_no' => $data['alternativeNumber'] ?? null,
            'dob' => $data['dob'] ?? null,
            'otp_expires_at' => now()->addMinutes(5),
            'vehicle_brand' => $data['vehicleBrand'] ?? null,
            'vehicle_plate_no' => $data['vehiclePlateNumber'] ?? null,
            'vehicle_capicty' => $data['vehicleCapicty'] ?? null,
            'vehicle_production_year' => $data['vehicleProductionYear'] ?? null,
            'commercial_permit_year' => $data['commercialPermitYear'] ?? null,
            'profile' => $data['profile'] ?? null,
            'vehicle_photo' => $data['vehiclePhoto'] ?? null,
            'vehicle_rc_front_photo' => $data['vehicleRegistrationCertificateFrontPhoto'] ?? null,
            'vehicle_rc_back_photo' => $data['vehicleRegistrationCertificateBackPhoto'] ?? null,
        ]);

        // Create verification
        $verification = DriverVerification::create([
            'driver_id' => $driver->id,
            'licence_no' => $data['licenceNumber'] ?? null,
            'licence_expire_date' => $data['licenceExpireDate'] ?? null,
            'licence_front_photo' => $data['licenceFrontPhoto'] ?? null,
            'licence_back_photo' => $data['licenceBackPhoto'] ?? null,
            'selfie_with_driver_licence' => $data['selfieWithDriverLicence'] ?? null,
            'aadhar_no' => $data['aadharNumber'] ?? null,
            'aadhar_front_photo' => $data['aadharFrontPhoto'] ?? null,
            'aadhar_back_photo' => $data['aadharBackPhoto'] ?? null,
            'police_verification_certificate' => $data['policeVerificationCertificate'] ?? null,
        ]);
        return response()->json([
            'status' => true,
            // 'message' => 'Signup initiated. OTP sent to phone.'
            'message' => 'Driver and verification created successfully.',
            // 'driver' => $driver->load('verification')
           'driver' => new DriverResource($driver->load('verification')),
        ]);
    }
}
