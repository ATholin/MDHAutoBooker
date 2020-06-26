<?php

namespace App\Jobs;

use App\ScheduledBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteOldScheduled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * Delete all scheduled bookings that are a week old.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::now('Europe/Stockholm')->subWeek();
        $bookings = ScheduledBooking::whereDate('date', '<', $date)->get();

        $cnt = $bookings->count();

        foreach ($bookings as $booking) {
            $booking->delete();
        }

        Log::info("Successfully deleted {$cnt} bookings");
    }
}
