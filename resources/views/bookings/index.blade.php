@inject('kronox', 'App\Services\KronoxService')
@extends('layouts.app')

@section('content')
    @include('partials.h1', ['text' => 'Bookings'])

    <label class="block my-4">
        <span class="text-gray-700 block">Date</span>
        <form id="form" action="{{ route('home') }}">
            <input type="date" class="mt-1 form-control px-4 py-2 rounded shadow"
                   onblur="document.getElementById('form').submit()"
                   id="date" name="date" value="{{$date}}" min="{{ now()->format('Y-m-d') }}">
            <select class="mt-1 form-select px-4 py-2 rounded shadow" id="flik" name="flik" onchange="document.getElementById('form').submit()">
                <option value="FLIK_0001" {{ request('flik') == 'FLIK_0001' ? "selected" : ""  }}>Västerås</option>
                <option value="FLIK_0010" {{ request('flik') == 'FLIK_0010' ? "selected" : ""  }}>Eskilstuna</option>
            </select>
        </form>
    </label>

    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Room
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            08:15 - 10:00
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            10:15 - 12:00
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            12:15 - 14:00
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            14:15 - 16:00
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            16:15 - 18:00
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            18:15 - 20:00
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse ($rows as $ri => $row)
                        <tr>
                            @foreach ($row as $i => $cell)
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    @if ($i === 0)
                                        <div class="text-sm font-medium text-gray-900">{{ $cell['text'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $cell['tooltip'] }}</div>
                                    @elseif ($cell["text"] == "Free")
                                        <x-modal x-cloak>
                                            <button class="inline-flex leading-5 font-medium text-green-500"
                                                    @click="showModal = true">
                                                {{ $cell['text'] }}
                                            </button>

                                            <x-slot name="title">Book</x-slot>

                                            <x-slot name="content">
                                                <form id="{{ $ri.$i }}" action="{{ route('bookings.book') }}"
                                                      method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ request('flik') ?? 'FLIK_0001' }}" name="flik" >

                                                    <label class="block mt-4">
                                                        <span class="text-gray-700">Account</span>
                                                        <select name="kronox_credentials_id" id="credentials"
                                                                class="form-select mt-1 block w-full" required>
                                                            @foreach(auth()->user()->credentials as $credential)
                                                                <option value="{{ $credential->id }}">
                                                                    {{ $credential->username }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </label>

                                                    <label class="block mt-4">
                                                        <span class="text-gray-700">Date</span>
                                                        <input type="date" class="form-input mt-1 block w-full"
                                                               id="date" name="date" required value="{{ $date }}">
                                                    </label>

                                                    <label class="block mt-4">
                                                        <span class="text-gray-700">Time</span>
                                                        <select class="form-select mt-1 block w-full" id="interval"
                                                                name="interval" required>
                                                            @foreach(\App\Facades\Kronox::getIntervals() as $interval)
                                                                <option
                                                                    value="{{ $interval['interval'] }}" {{ $i - 1 === $interval['interval'] ? 'selected' : '' }}>
                                                                    {{ $interval['time'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </label>

                                                    <label class="block mt-4">
                                                        <span class="text-gray-700">Room</span>
                                                        <select class="form-select mt-1 block w-full" id="room"
                                                                name="room" required>
                                                            @foreach($kronox->getRooms(request('flik') ?? 'FLIK_0001') as $room)
                                                                <option{{ $room == $row[0]['text'] ? ' selected' : '' }}>{{ $room }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>

                                                    <label class="block mt-4">
                                                        <span class="text-gray-700">Message</span>
                                                        <input type="text" class="form-input mt-1 block w-full"
                                                               id="message" name="message"/>
                                                    </label>

                                                </form>
                                            </x-slot>

                                            <x-slot name="footer">
                                                <button type="submit" form="{{ $ri.$i }}"
                                                        class="px-4 py-2 text-sm font-medium text-green-800 bg-green-300 border border-transparent rounded-md hover:bg-green-200 focus:outline-none focus:border-green-700 focus:shadow-outline-indigo active:bg-green-400 transition duration-150 ease-in-out"
                                                        type="submit">Submit
                                                </button>
                                                <button
                                                    class="ml-1 px-4 py-2 text-sm font-medium text-gray-600 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus:shadow-outline-indigo active:bg-gray-400 transition duration-150 ease-in-out"
                                                    @click="showModal = false">Close
                                                </button>
                                            </x-slot>
                                        </x-modal>
                                    @else
                                        @if(strlen($cell["text"]) == 5)
                                            <span
                                                class="inline-flex font-medium bg-red-200 rounded-full px-2 text-red-800"
                                                title="{{ isset($cell['tooltip']) ? $cell['tooltip'] : '' }}">
                                                    {{ $cell['text'] }}
                                                </span>
                                        @elseif($friend = auth()->user()->friends()->whereMdhUsername($cell['text'])->first())
                                            <span class="inline-flex font-medium rounded-full px-2 text-black"
                                                  style="background-color: {{ $friend->color }};"
                                                  title="{{ $friend->name . ': ' . (isset($cell['tooltip']) ? $cell['tooltip'] : '') }}">
                                                    {{ $cell['text'] }}
                                                </span>
                                        @else
                                            <span class="inline-flex leading-5 font-medium text-red-500"
                                                  title="{{ isset($cell['tooltip']) ? $cell['tooltip'] : '' }}">
                                                    {{ $cell['text'] }}
                                                </span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                            Add some <a href="{{ route('credentials.index') }}"
                                        class="text-indigo-500 hover:text-indigo-600">credentials</a> to see bookings.
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                       </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                       </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                       </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                        </td>
                    @endforelse
                    {{--                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">--}}
                    {{--                            <div class="flex items-center">--}}
                    {{--                                <div class="flex-shrink-0 h-10 w-10">--}}
                    {{--                                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1532910404247-7ee9488d7292?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />--}}
                    {{--                                </div>--}}
                    {{--                                <div class="ml-4">--}}
                    {{--                                    <div class="text-sm leading-5 font-medium text-gray-900">Bernard Lane--}}
                    {{--                                    </div>--}}
                    {{--                                    <div class="text-sm leading-5 text-gray-500">bernardlane@example.com--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </td>--}}
                    {{--                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">--}}
                    {{--                            <div class="text-sm leading-5 text-gray-900">Director--}}
                    {{--                            </div>--}}
                    {{--                            <div class="text-sm leading-5 text-gray-500">Human Resources--}}
                    {{--                            </div>--}}
                    {{--                        </td>--}}
                    {{--                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">--}}
                    {{--              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">--}}
                    {{--                Active--}}
                    {{--              </span>--}}
                    {{--                        </td>--}}
                    {{--                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">--}}
                    {{--                            Owner--}}
                    {{--                        </td>--}}
                    {{--                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">--}}
                    {{--                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit--}}
                    {{--                            </a>--}}
                    {{--                        </td>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
