<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class OrganizationAuthController extends Controller
{

    public function login(Request $request)
    {
          $credentials = $request->only('email', 'password');

        if (Auth::guard('organization')->attempt($credentials)) {
            $organization = Auth::guard('organization')->user();
            $token = JWTAuth::fromUser($organization);
            // $token = $organization->createToken('access_token')->accessToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function checkTokenExpiration(Request $request)
{
    $token = $request->token;

    try {
        // Check if the token is valid and get the authenticated organization
        $user = Auth::guard('organization')->setToken($token)->authenticate();

        // Check if the token's expiration time (exp) is greater than the current timestamp
        $isExpired = JWTAuth::setToken($token)->checkOrFail();

        return response()->json(['message' => 'Token is valid', 'organization' => $user], 200);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        // Token has expired
        return response()->json(['message' => 'Token has expired'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        // Token is invalid
        return response()->json(['message' => 'Invalid token'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // Token not found or other JWT exception
        return response()->json(['message' => 'Error while processing token'], 500);
    }
}




public function logout(Request $request)
{
    try {
        $token = $request->bearerToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['message' => 'Error while processing token'], 500);
    }
}

    public function checkToken(Request $request)
    {
        $organization = $request->user('organization');
        if ($organization) {
            return response()->json(['message' => 'Token is valid']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }



}
