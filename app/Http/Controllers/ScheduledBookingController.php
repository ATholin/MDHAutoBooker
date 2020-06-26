<?php

namespace App\Http\Controllers;

use App\Facades\Kronox;
use App\ScheduledBooking;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ScheduledBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        return view('scheduled.index', [
            'bookings' => $request->user()->bookings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function book(Request $request)
    {
        $validated = $request->validate([
            'interval' => 'required|integer|between:0,5',
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:10',
            'message' => 'nullable|string|max:255',
            'recurring' => 'sometimes',
            'kronox_credentials_id' => 'exists:App\KronoxCredentials,id'
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

    public function addNextWeek(ScheduledBooking $scheduled)
    {
        $new = $scheduled->replicate();
        $new->date = Carbon::parse($new->date)->addWeek()->toDateString();
        $new->result = null;
        $new->save();

        return back();
    }

    public function setRecurring(Request $request, ScheduledBooking $scheduled)
    {
        $scheduled->recurring = $request->checked ? 1 : 0;
        $scheduled->save();

        // It's for ajax requests, but i guess we have to return something
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ScheduledBooking $scheduledBooking
     * @return Response
     */
    public function edit(ScheduledBooking $scheduledBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ScheduledBooking $scheduledBooking
     * @return Response
     */
    public function update(Request $request, ScheduledBooking $scheduledBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ScheduledBooking $scheduled
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ScheduledBooking $scheduled)
    {
        $scheduled->delete();
        return back();
    }
}
