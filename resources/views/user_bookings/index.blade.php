@extends('layouts.app')

@section('content')
    @include('partials.h1', ['text' => 'My Bookings'])

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
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm leading-5 font-medium text-gray-900">{{ $booking->time }}
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">{{ $booking->date }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $booking->room }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $booking->booker }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap truncate border-b border-gray-200 text-sm leading-5 text-gray-500"
                                title="{{ $booking->message }}">
                                {{ $booking->message }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">

                                <form method="POST" action="{{ route('user_bookings.makeRecurring') }}">
                                    @csrf

                                    <input type="hidden" value="{{ json_encode($booking) }}" name="booking" >

                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">Make recurring
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('user_bookings.unBook') }}">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}"/>
                                    <input type="hidden" name="booker" value="{{ $booking->booker }}"/>

                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
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
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
