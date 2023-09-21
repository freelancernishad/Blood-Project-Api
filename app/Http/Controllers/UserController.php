<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
         $user->blood_group = $request->blood_group;
         $user->email = $request->email;
         $user->gander = $request->gander;
         $user->gardiant_phone = $request->gardiant_phone;
         $user->last_donate_date = $request->last_donate_date;
         $user->whatsapp_number = $request->whatsapp_number;
         $user->division = $request->division;
         $user->district = $request->district;
         $user->thana = $request->thana;
         $user->union = $request->union;
         $user->org = $request->org;

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
         $user = User::with(['organization','donationLogs'])->find($id);

         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         return response()->json($user, 200);
     }


     public function changePassword(Request $request)
     {
         $validator = Validator::make($request->all(), [
            'current_password' => 'required',
             'new_password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $user = Auth::guard('web')->user();
         if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
         }
         $user->password = Hash::make($request->new_password);
         $user->save();
         return response()->json(['message' => 'Password changed successfully.'], 200);
     }


}
