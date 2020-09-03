<?php

namespace App\Http\Controllers;

use App\Facades\Kronox;
use App\ScheduledBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $credential = $request->user()->credentials()->first();

        $date = Carbon::parse($request->input('date'));

        if (! $date || $date->lt(now()->subDay())) {
            return redirect()->route('home', [
                'date' => now()->format('Y-m-d'),
            ]);
        }

        if (! $credential) {
            return view('bookings.index', [
                'rows' => [],
                'date' => $date->format('Y-m-d'),
            ]);
        }

        $rows = Kronox::all($credential->session, $date);

        return view('bookings.index', [
            'rows' => $rows,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'interval' => 'required|integer|between:0,5',
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:10',
            'message' => 'nullable|string|max:255',
            'recurring' => 'sometimes|boolean',
            'kronox_credentials_id' => 'exists:App\KronoxCredentials,id',
        ]);

        /** @var ScheduledBooking $booking */
        $booking = ScheduledBooking::make($validated);
        $date = Carbon::parse($request->date);
        $booking->date = $date;

        // Schedule booking if more than a week out
        if ($date->gt(now()->addWeek())) {
            // Save the booking to the database.
            $booking->save();

            return back();
        }

        Kronox::book($booking);

        return back();
    }
}
