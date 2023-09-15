<?php

namespace App\Http\Controllers;

use App\Models\DonationLog;
use Illuminate\Http\Request;

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
        DonationLog::create($request->all());
        return redirect()->route('donation-logs.index')->with('success', 'Donation log created successfully');
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
