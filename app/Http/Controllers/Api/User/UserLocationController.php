<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use App\Models\UserLocationHistory;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = $request->user()->locations;
        return response()->json(['status' => true, 'locations' => $locations]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_type' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $location = new UserLocationHistory();
        $location->user_id = $request->user()->id;
        $location->location_type = $data['location_type'];
        $location->address = $data['address'];
        $location->save();

        return response()->json(['status' => true, 'message' => 'Location added successfully.', 'location' => $location]);
    }

    public function show($id)
    {
        $location = UserLocationHistory::findOrFail($id);
        return response()->json(['status' => true, 'location' => $location]);
    }

    public function update(Request $request, $id)
    {
        $location = UserLocationHistory::findOrFail($id);
        
        if ($request->user()->id !== $location->user_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'location_type' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
        ]);

        $location->update($data);

        return response()->json(['status' => true, 'message' => 'Location updated successfully.', 'location' => $location]);
    }

    public function destroy(Request $request, $id)
    {
        $location = UserLocationHistory::findOrFail($id);

        if ($request->user()->id !== $location->user_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $location->delete();

        return response()->json(['status' => true, 'message' => 'Location deleted successfully.']);
    }
}

