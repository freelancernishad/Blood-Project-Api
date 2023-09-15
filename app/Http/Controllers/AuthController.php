<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];



        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // $token = JWTAuth::fromUser($user);
            $token = JWTAuth::fromUser($user, ['guard' => 'user']);
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }



public function checkTokenExpiration(Request $request)
{

$token = $request->token;


    try {

        $payload = JWTAuth::setToken($token)->getPayload();

        // Check if the token's expiration time (exp) is greater than the current timestamp
        $isExpired = $payload->get('exp') < time();

        $user = Auth::guard('web')->setToken($token)->authenticate();
        // $user = JWTAuth::setToken($token)->authenticate();
        return response()->json(['message' => 'Token is valid', 'user' => $user], 200);
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
                return response()->json(['message' => 'Logged out successfully'], 200);
            } else {
                return response()->json(['message' => 'Invalid token'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Error while processing token'], 500);
        }
    }



         // User registration
         public function register(Request $request)
         {


             $validator = Validator::make($request->all(), [
                 'name' => 'required|string|max:255',
                 'mobile' => [
                     'required',
                     'string',
                     'max:15',
                     Rule::unique('users'),
                 ],
                 'blood_group' => 'required|string|max:5',
                 'email' => 'required|string|email|max:255|unique:users',
                 'gender' => 'required|string|max:10',
                 'guardian_phone' => 'required|string|max:15',
                 'last_donate_date' => 'required|date',
                 'whatsapp_number' => 'required|string|max:15',
                 'division' => 'required|string|max:255',
                 'district' => 'required|string|max:255',
                 'thana' => 'required|string|max:255',
                 'union' => 'required|string|max:255',
                 'org' => 'nullable|string|max:255',
                 'password' => 'required|string|min:8',
             ]);

             if ($validator->fails()) {
                 return response()->json(['errors' => $validator->errors()], 400);
             }

             $user = new User([
                 'name' => $request->name,
                 'mobile' => $request->mobile,
                 'blood_group' => $request->blood_group,
                 'email' => $request->email,
                 'gender' => $request->gender,
                 'guardian_phone' => $request->guardian_phone,
                 'last_donate_date' => $request->last_donate_date,
                 'whatsapp_number' => $request->whatsapp_number,
                 'division' => $request->division,
                 'district' => $request->district,
                 'thana' => $request->thana,
                 'union' => $request->union,
                 'org' => $request->org,
                 'password' => Hash::make($request->password),
             ]);

             $user->save();



             $token = JWTAuth::fromUser($user);
             return response()->json(['token' => $token], 201);
             // You can generate a JWT token here and return it if needed
             // Refer to your JWT library's documentation for this

            //  return response()->json(['message' => 'User registered successfully'], 201);
         }











}
