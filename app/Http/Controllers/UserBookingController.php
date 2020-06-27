<?php

namespace App\Http\Controllers;

use App\Facades\Kronox;
use App\KronoxCredentials;
use App\ScheduledBooking;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $bookings = auth()->user()->credentials->map(function (KronoxCredentials $credential) {
            return Kronox::bookings($credential->session);
        })->flatten(1);

        return view('user_bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Unbook from Kronox
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function book(Request $request)
    {
        $validated = $request->validate([
            'interval' => 'required|integer|between:0,5',
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:10',
            'message' => 'nullable|string|max:255',
            'recurring' => 'sometimes|boolean',
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

    /**
     * Unbook from Kronox
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function unBook(Request $request)
    {

        $validated = $request->validate([
            'booker' => 'required|exists:App\KronoxCredentials,username',
            'bookingID' => 'required|string'
        ]);

        $username = $validated['booker'];

        if (! auth()->user()->credentials()->whereUsername($username)->exists()) {
            return back();
        }

        Kronox::unBook($validated['booker'], $validated['bookingID']);

        return back();
    }

    /**
     * Make a booking recurring
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function makeRecurring(Request $request)
    {
        $data = json_decode($request->input('booking'));

        $newDate = Carbon::parse($data->date)->addWeek();


        $existing = ScheduledBooking::whereDate('date', $newDate)
            ->whereInterval(Kronox::timeToInterval($data->time))
            ->whereRoom($data->room);

        if ($existing->exists()) {
            return back();
        }

        $request->user()->credentials()->firstWhere('username', $data->booker)->bookings()->create([
            'date' => $newDate,
            'interval' => Kronox::timeToInterval($data->time),
            'room' => $data->room,
            'message' => $data->message,
            'recurring' => true,
        ]);

        return redirect()->route('scheduled_booking.index');
    }
}
