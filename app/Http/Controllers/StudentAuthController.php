<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
          $credentials = $request->only('founder_email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            $token = JWTAuth::fromUser($student);
            // $token = $student->createToken('access_token')->accessToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function checkTokenExpiration(Request $request)
{
    $token = $request->token;

    try {
        // Check if the token is valid and get the authenticated student
        $user = Auth::guard('student')->setToken($token)->authenticate();

        // Check if the token's expiration time (exp) is greater than the current timestamp
        $isExpired = JWTAuth::setToken($token)->checkOrFail();

        return response()->json(['message' => 'Token is valid', 'student' => $user], 200);
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
        $student = $request->user('student');
        if ($student) {
            return response()->json(['message' => 'Token is valid']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
