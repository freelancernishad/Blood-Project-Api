<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class OrganizationController extends Controller
{

    // Organization registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'string',
                'max:15',
                Rule::unique('organizations'),
            ],
            'email' => 'required|string|email|max:255|unique:organizations',
            'whatsapp_number' => 'required|string|max:15',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'thana' => 'required|string|max:255',
            'union' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $organization = new Organization([
            'logo' => $request->logo,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'division' => $request->division,
            'district' => $request->district,
            'thana' => $request->thana,
            'union' => $request->union,
            'password' => Hash::make($request->password),
        ]);

        $organization->save();



        $token = JWTAuth::fromUser($organization);
        return response()->json(['token' => $token], 201);
        // You can generate a JWT token here and return it if needed
        // Refer to your JWT library's documentation for this

        return response()->json(['message' => 'Organization registered successfully'], 201);
    }

    // Organization update
    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'string',
                'max:15',
                Rule::unique('organizations')->ignore($organization->id),
            ],
            // Add validation rules for other fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $organization->logo = $request->logo;
        $organization->name = $request->name;
        $organization->mobile = $request->mobile;
        // Update other fields here

        $organization->save();

        return response()->json(['message' => 'Organization updated successfully'], 200);
    }

    // Organization delete
    public function delete($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $organization->delete();

        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }

    // Show organization details
    public function show($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        return response()->json($organization, 200);
    }



    public function getDonersByOrganization(Request $request)
    {
        $perpage = 20;
        if($request->perpage){
            $perpage = $request->perpage;
        }

        $organization = Auth::guard('organization')->user();
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $users = User::with(['organization','donationLogs'])->where('org', $organization->id)->orderBy('id','desc')->paginate($perpage);
        return response()->json($users, 200);
    }





}
