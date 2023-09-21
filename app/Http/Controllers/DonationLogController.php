<?php

namespace App\Http\Controllers;

use App\Models\DonationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationLogController extends Controller
{
    public function index()
    {
        $donationLogs = DonationLog::all();
        return view('donation-logs.index', compact('donationLogs'));
    }

    public function create()
    {
        return view('donation-logs.create');
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required',
            'blood_taker_name' => 'required',
            'blood_taker_phone' => 'required',
            'address' => 'required',
            'hospital' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        $user_id = $request->user_id;
        $date = date("Y-m-d", strtotime($request->date));
        $user = User::find($user_id);
        $user->last_donate_date = $date;
        $user->save();

        DonationLog::create($request->all());
        return response()->json(['message' => 'Donation log created successfully'], 201);





    }

    public function edit(DonationLog $donationLog)
    {
        return view('donation-logs.edit', compact('donationLog'));
    }

    public function update(Request $request, DonationLog $donationLog)
    {
        $donationLog->update($request->all());
        return redirect()->route('donation-logs.index')->with('success', 'Donation log updated successfully');
    }

    public function destroy(DonationLog $donationLog)
    {
        $donationLog->delete();
        return redirect()->route('donation-logs.index')->with('success', 'Donation log deleted successfully');
    }
}
