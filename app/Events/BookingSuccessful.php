<?php

namespace App\Events;

use App\ScheduledBooking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingSuccessful
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ScheduledBooking $booking;

    /**
     * Create a new event instance.
     *
     * @param ScheduledBooking $booking
     */
    public function __construct(ScheduledBooking $booking)
    {
        $this->booking = $booking;
    }
}
