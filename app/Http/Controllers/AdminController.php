<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organizations',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        $admin = new Admin([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $admin->save();
        return response()->json(['message' => 'Admin registered successfully'], 201);
        // Return a response or redirect
    }

    public function update(Request $request, $id)
    {
        // Find and update the admin
    }

    public function delete($id)
    {
        // Find and delete the admin
    }

    // Add other functions as needed
}
