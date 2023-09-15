<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

     // User update
     public function update(Request $request, $id)
     {
         $user = User::find($id);

         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
             'mobile' => [
                 'required',
                 'string',
                 'max:15',
                 Rule::unique('users')->ignore($user->id),
             ],
             // Add validation rules for other fields as needed
         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 400);
         }

         $user->name = $request->name;
         $user->mobile = $request->mobile;
         // Update other fields here

         $user->save();

         return response()->json(['message' => 'User updated successfully'], 200);
     }

     // User delete
     public function delete($id)
     {
         $user = User::find($id);

         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         $user->delete();

         return response()->json(['message' => 'User deleted successfully'], 200);
     }

     // Show user details
     public function show($id)
     {
         $user = User::find($id);

         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         return response()->json($user, 200);
     }
}
