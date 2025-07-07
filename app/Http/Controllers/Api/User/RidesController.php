<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RidesController extends Controller
{

    public function searchRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickupLocation' => 'required|string',
            'dropLocation' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        // Step 1: Geocode both locations
        // $pickupCoords = $this->getLatLngFromAddress($request->pickupLocation);
        // $dropCoords = $this->getLatLngFromAddress($request->dropLocation);

        // if (!$pickupCoords || !$dropCoords) {
        //     return response()->json(['error' => 'Could not fetch coordinates'], 400);
        // }

        // // Step 2: Distance
        // $distance = $this->calculateDistance(
        //     $pickupCoords['lat'],
        //     $pickupCoords['lng'],
        //     $dropCoords['lat'],
        //     $dropCoords['lng']
        // );

        $distance = 10.5;
        // Step 3: Time (30 km/h speed)
        $estimatedTime = ceil(($distance / 30) * 60); // in minutes

        // Step 4: Fare Calculations
        $fareOptions = [
            'bike' => $this->calculateTieredFare($distance, 30, 5),   // base: 30, per km: 5
            'car'  => $this->calculateTieredFare($distance, 50, 15),  // base: 50, per km: 15
            'auto' => $this->calculateTieredFare($distance, 40, 10),  // base: 40, per km: 10
        ];

        // Step 5: Return response
        return response()->json([
            'requestId' => 'REQ' . rand(100000, 999999),
            'distanceKm' => round($distance, 2),
            'estimatedTime' => "{$estimatedTime} mins",
            'pickupLocation' => $request->pickupLocation,
            'dropLocation' => $request->dropLocation,
            'fareOptions' => $fareOptions
        ]);
    }

    // Fare logic based on first 3km included
    private function calculateTieredFare($distance, $baseRate, $perKmRate)
    {
        $extraDistance = max(0, $distance - 3);
        return round($baseRate + ($extraDistance * $perKmRate), 2);
    }

    //  Geocoding
    private function getLatLngFromAddress($address)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

        $response = Http::get($url);
        if ($response->successful() && isset($response['results'][0]['geometry']['location'])) {
            return [
                'lat' => $response['results'][0]['geometry']['location']['lat'],
                'lng' => $response['results'][0]['geometry']['location']['lng'],
            ];
        }

        return null;
    }

    // Distance calculator
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function bookRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requestId' => 'required|string',
            'pickupLocation' => 'required|string',
            'dropLocation' => 'required|string',
            'pickupLat' => 'nullable|numeric',
            'pickupLng' => 'nullable|numeric',
            'dropLat' => 'nullable|numeric',
            'dropLng' => 'nullable|numeric',
            'vehicleType' => 'required|in:1,2,3', // 1 = Bike, 2 = Car, 3 = Auto
            'rideType' => 'nullable|in:0,1',  // 0 = Passenger, 1 = Parcel
            'numberOfPassenger' => 'required_if:rideType,0|nullable|integer|min:1',
            'weight' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'height' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'length' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'breadth' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'distanceKm' => 'required|numeric|min:0',
            'estimatedTime' => 'required|string',
            'finalAmount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $ride = new Ride();
        $ride->user_id = Auth::id(); // Assuming user is authenticated
        $ride->request_id = $request->requestId;
        $ride->pick_up_location = $request->pickupLocation;
        $ride->drop_location = $request->dropLocation;
        $ride->pickup_lat = $request->pickupLat;
        $ride->pickup_lng = $request->pickupLng;
        $ride->drop_lat = $request->dropLat;
        $ride->drop_lng = $request->dropLng;
        $ride->vehicle_type = $request->vehicleType;
        $ride->ride_type = $request->rideType;
        $ride->number_of_passenger = $request->numberOfPassenger;
        $ride->weight = $request->weight;
        $ride->height = $request->height;
        $ride->length = $request->length;
        $ride->breadth = $request->breadth;
        $ride->distance_km = $request->distanceKm;
        $ride->estimated_time = $request->estimatedTime;
        $ride->final_amount = $request->finalAmount;
        $ride->status = 1; // 1 = Booked
        $ride->save();

        return response()->json([
            'success' => true,
            'message' => 'Ride booked successfully.',
            'ride' => [
                'rideId' => $ride->id,
                'requestId' => $ride->request_id,
                'pickupLocation' => $ride->pick_up_location,
                'dropLocation' => $ride->drop_location,
                'pickupLat' => $ride->pickup_lat,
                'pickupLng' => $ride->pickup_lng,
                'dropLat' => $ride->drop_lat,
                'dropLng' => $ride->drop_lng,
                'vehicleType' => $ride->vehicle_type,
                'rideType' => $ride->ride_type,
                'numberOfPassenger' => $ride->number_of_passenger,
                'weight' => $ride->weight,
                'height' => $ride->height,
                'length' => $ride->length,
                'breadth' => $ride->breadth,
                'distanceKm' => $ride->distance_km,
                'estimatedTime' => $ride->estimated_time,
                'finalAmount' => $ride->final_amount,
                'status' => $ride->status,
            ]
        ]);
    }

    public function offerFareRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requestId' => 'required|string',
            'offerFare' => 'required|numeric',
            'vehicleType' => 'required|in:1,2,3'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $ride = Ride::where('request_id', $request->requestId)->first();
        if (!$ride) {
            return response()->json(['status' => false, 'message' => 'Invalid request ID.'], 404);
        }

        $distance = $ride->distance_km;

        $typeMap = [1 => 'bike', 2 => 'car', 3 => 'auto'];
        $vehicleType = $typeMap[$request->vehicleType];

        $fareRates = [
            'bike' => ['base' => 30, 'perKm' => 5],
            'car'  => ['base' => 50, 'perKm' => 15],
            'auto' => ['base' => 40, 'perKm' => 10],
        ];

        $rate = $fareRates[$vehicleType];
        $fare = $this->calculateTieredFare($distance, $rate['base'], $rate['perKm']);
        $estimatedTime = ceil(($distance / 30) * 60);

        return response()->json([
            'success' => true,
            'message' => 'Offer submitted successfully',
            'vehicleType' => $vehicleType,
            'distanceKm' => round($distance, 2),
            'estimatedTime' => "{$estimatedTime} mins",
            'fare' => $fare,
            'offeredFare' => $request->offerFare,
            'baseRate' => $rate['base'],
            'perKmRate' => $rate['perKm'],
            'extraDistanceFare' => max(0, $distance - 3) * $rate['perKm'],
        ]);
    }

    public function cancelRide(Request $request)
    {
        $request->validate([
            'requestId' => 'required|exists:rides,request_id'
        ]);

          $ride = Ride::where('request_id', $request->requestId)->first();

        if ($ride->status != 1) {
            return response()->json(['success' => false, 'message' => 'Only booked rides can be cancelled.'], 400);
        }

        $ride->status = 4; // Cancelled
        $ride->cancel_type = 0; 
        $ride->save();

        return response()->json(['success' => true, 'message' => 'Ride cancelled successfully.']);
    }

    public function editRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requestId' => 'required|exists:rides,request_id',
            'pickupLocation' => 'nullable|string',
            'dropLocation' => 'nullable|string',
            'pickupLat' => 'nullable|numeric',
            'pickupLng' => 'nullable|numeric',
            'dropLat' => 'nullable|numeric',
            'dropLng' => 'nullable|numeric',
            'rideType' => 'nullable|in:0,1',
            'numberOfPassenger' => 'required_if:rideType,0|nullable|integer|min:1',
            'weight' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'height' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'length' => 'required_if:rideType,1|nullable|numeric|min:0.1',
            'breadth' => 'required_if:rideType,1|nullable|numeric|min:0.1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

          $ride = Ride::where('request_id', $request->requestId)->first();

        if ($ride->status != 1) {
            return response()->json(['success' => false, 'message' => 'Only booked rides can be edited.'], 400);
        }

        // Mapping camelCase to snake_case
        $ride->pick_up_location = $request->pickupLocation ?? $ride->pick_up_location;
        $ride->drop_location = $request->dropLocation ?? $ride->drop_location;
        $ride->pickup_lat = $request->pickupLat ?? $ride->pickup_lat;
        $ride->pickup_lng = $request->pickupLng ?? $ride->pickup_lng;
        $ride->drop_lat = $request->dropLat ?? $ride->drop_lat;
        $ride->drop_lng = $request->dropLng ?? $ride->drop_lng;
        $ride->ride_type = $request->rideType ?? $ride->ride_type;
        $ride->number_of_passenger = $request->numberOfPassenger ?? $ride->number_of_passenger;
        $ride->weight = $request->weight ?? $ride->weight;
        $ride->height = $request->height ?? $ride->height;
        $ride->length = $request->length ?? $ride->length;
        $ride->breadth = $request->breadth ?? $ride->breadth;

        $ride->save();

        // Format response in camelCase
        return response()->json([
            'success' => true,
            'message' => 'Ride updated successfully.',
            'ride' => [
                'id' => $ride->id,
                'pickupLocation' => $ride->pick_up_location,
                'dropLocation' => $ride->drop_location,
                'pickupLat' => $ride->pickup_lat,
                'pickupLng' => $ride->pickup_lng,
                'dropLat' => $ride->drop_lat,
                'dropLng' => $ride->drop_lng,
                'rideType' => $ride->ride_type,
                'numberOfPassenger' => $ride->number_of_passenger,
                'weight' => $ride->weight,
                'height' => $ride->height,
                'length' => $ride->length,
                'breadth' => $ride->breadth,
            ]
        ]);
    }
}
