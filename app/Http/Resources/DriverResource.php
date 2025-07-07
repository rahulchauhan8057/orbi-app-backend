<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstname,
            'lastName' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone_no,
            'dob' => $this->dob,
            'profile' => asset('storage/' . $this->profile),
            'vehicleDetails' => [
                'vehicleBrand' => $this->vehicle_brand,
                'vehiclePlateNumber' => $this->vehicle_plate_no,
                'vehicleCapicty' => $this->vehicle_capicty,
                'vehicleProductionYear' => $this->vehicle_production_year,
                'commercialPermitYear' => $this->commercial_permit_year,
                'vehiclePhoto' => asset('storage/' . $this->vehicle_photo),
            ],
            'verification' => new DriverVerificationResource($this->whenLoaded('verification')),
        ];
    }
}
