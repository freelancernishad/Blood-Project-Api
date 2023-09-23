<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

     public function filterUsers(Request $request)
     {


        $validator = Validator::make($request->all(), [
            'blood_group' => 'required',
            'filter_by' => 'required|in:union,org', // Assuming you have a select input for this
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }



         // Get the selected blood_group and filter_by values from the request
         $bloodGroup = $request->input('blood_group');

         $bloodGrade = explode(',',$bloodGroup);
        if($bloodGrade[1]=='p'){
            $bloodGroup = $bloodGrade[0]."+";
        }else{
            $bloodGroup = $bloodGrade[0]."-";
        }



         $filterBy = $request->input('filter_by');

         // Calculate the date 4 months ago
         $fourMonthsAgo = Carbon::now()->subMonths(4);
         $fourMonthsAgo = date('Y-m-d',strtotime($fourMonthsAgo));

         // Initialize a query builder
         $query = User::query();
         $query->with(['organization', 'donationLogs']);

         // Add the blood_group condition
         $query->where('blood_group', $bloodGroup);
         // Add the filter_by condition (either union or org)
         if ($filterBy === 'union') {
             $query->where('union', $request->input('search'));
         } elseif ($filterBy === 'org') {
             $query->where('org', $request->input('search'));
         }

         // Add the condition for last_donate_date over 4 months old

         $query->where('last_donate_date', '<', $fourMonthsAgo);

         // Execute the query and retrieve the filtered users

         $perpage = 20;
        //  if($request->perpage){
        //      $perpage = $request->perpage;
        //  }

         $filteredUsers = $query->paginate($perpage);

         // Return the filtered users as JSON response
         return response()->json(['doners' => $filteredUsers],200);
     }



     public function listDonatedUsers(Request $request)
     {
        $perpage = 20;
        //  if($request->perpage){
        //      $perpage = $request->perpage;
        //  }


         // Retrieve users who have at least one donation log
         $donatedUsers = User::has('donationLogs')->with(['organization', 'donationLogs'])->paginate($perpage);

         return response()->json(['donated_users' => $donatedUsers]);
     }

}
