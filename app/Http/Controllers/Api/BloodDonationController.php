<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodDonation;
use Illuminate\Http\Request;

class BloodDonationController extends Controller
{
    /**
     * Store a new blood donation registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_donation_date' => 'required|date|before_or_equal:today',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'phone' => 'required|string|max:20',
        ]);

        $donation = BloodDonation::create($validated);

        return response()->json([
            'message' => 'Blood donation registration submitted successfully',
            'data' => $donation,
        ], 201);
    }

    /**
     * List all blood donation registrations (for admin API use if needed).
     */
    public function index()
    {
        $donations = BloodDonation::latest()->paginate(20);

        return response()->json($donations);
    }
}
