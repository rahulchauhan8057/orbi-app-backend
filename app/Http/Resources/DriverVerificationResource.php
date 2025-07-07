<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverVerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
  
         public function toArray($request)
    {
        return [
            'licenceNumber' => $this->licence_no,
            'licenceExpireDate' => $this->licence_expire_date,
            'licenceFrontPhoto' => asset('storage/' . $this->licence_front_photo),
            'licenceBackPhoto' => asset('storage/' . $this->licence_back_photo),
            'selfieWithDriverLicence' => asset('storage/' . $this->selfie_with_driver_licence),
            'aadharNumber' => $this->aadhar_no,
            'aadharFrontPhoto' => asset('storage/' . $this->aadhar_front_photo),
            'aadharBackPhoto' => asset('storage/' . $this->aadhar_back_photo),
            'policeVerificationCertificate' => $this->police_verification_certificate ? asset('storage/' . $this->police_verification_certificate) : null,
        ];
    }
}
