@extends('layouts.app')

@section('content')
    <div class="flex justify-between">
        @include('partials.h1', ['text' => 'Scheduled Bookings'])
        <x-modal x-cloak>
            <button
                class="px-4 py-2 text-sm font-medium text-indigo-800 bg-indigo-300 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-400 transition duration-150 ease-in-out"
                @click="showModal = true">
                Create scheduled
            </button>

            <x-slot name="title">Book</x-slot>

            <x-slot name="content">
                <form id="scheduled" action="{{ route('scheduled_booking.book') }}" method="POST">
                    @csrf

                    <label class="block mt-4">
                        <span class="text-gray-700">Account</span>
                        <select name="kronox_credentials_id" id="credentials" class="form-select mt-1 block w-full"
                                required>
                            @foreach(auth()->user()->credentials as $credential)
                                <option value="{{ $credential->id }}">
                                    {{ $credential->username }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Date</span>
                        <input type="date" class="form-input mt-1 block w-full" id="date" name="date" required
                               value="{{ now()->format('Y-m-d') }}">
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Time</span>
                        <select class="form-select mt-1 block w-full" id="interval" name="interval" required>
                            @foreach(\App\Facades\Kronox::getIntervals() as $interval)
                                <option value="{{ $interval['interval'] }}">
                                    {{ $interval['time'] }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Room</span>
                        <select class="form-select mt-1 block w-full" id="room" name="room" required>
                            @foreach(\App\Facades\Kronox::getRooms() as $room)
                                <option>{{ $room }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Message</span>
                        <input type="text" class="form-input mt-1 block w-full" id="message" name="message"/>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Recurring</span>
                        <input type="hidden" class="form-checkbox mt-1 block" id="recurring" name="recurring"
                               value="0"/>
                        <input type="checkbox" class="form-checkbox mt-1 block" id="recurring" name="recurring"
                               value="1"/>
                    </label>

                </form>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" form="scheduled"
                        class="px-4 py-2 text-sm font-medium text-green-800 bg-green-300 border border-transparent rounded-md hover:bg-green-200 focus:outline-none focus:border-green-700 focus:shadow-outline-indigo active:bg-green-400 transition duration-150 ease-in-out"
                        type="submit">Submit
                </button>
                <button
                    class="ml-1 px-4 py-2 text-sm font-medium text-gray-600 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus:shadow-outline-indigo active:bg-gray-400 transition duration-150 ease-in-out"
                    @click="showModal = false">Close
                </button>
            </x-slot>
        </x-modal>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Time
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Room
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Booker
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Message
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Recurring?
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Result
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center">
                                    <div>
                                        <div
                                            class="text-sm leading-5 font-medium text-gray-900">{{ \App\Facades\Kronox::intervalToTime($booking->interval) }}
                                        </div>
                                        <div
                                            class="text-sm leading-5 text-gray-500">{{ $booking->date->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $booking->room }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $booking->credentials->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate border-b border-gray-200 text-sm leading-5 text-gray-500"
                                title="{{ $booking->message }}">
                                {{ $booking->message }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate border-b border-gray-200 text-sm leading-5 text-gray-500"
                                title="{{ $booking->message }}">
                                {{ $booking->recurring ? 'Yes' : 'No' }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 leading-5 text-gray-500 text-xs"
                                title="{{ $booking->result }}">
                                {{ \Illuminate\Support\Str::limit($booking->result, 15, $end='...') }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">

                                <form method="POST" action="{{ route('scheduled_booking.destroy', $booking) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white">
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                                No bookings
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
