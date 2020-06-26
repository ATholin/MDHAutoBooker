<?php

namespace App\Jobs;

use App\ScheduledBooking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookScheduled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::now('Europe/Stockholm')->addWeek();
        $toBook = ScheduledBooking::whereDate('date', $date)->get();

        $cnt = $toBook->count();
        Log::info("$cnt scheduled bookings for $date.", $toBook->toArray());

        foreach ($toBook as $booking) {
            $booking->book();
        }
    }
}
