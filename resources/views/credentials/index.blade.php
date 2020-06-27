@extends('layouts.app')

@section('content')
    <div class="flex justify-between">
        @include('partials.h1', ['text' => 'Credentials'])
        <x-modal x-cloak>
            <button
                class="px-4 py-2 text-sm font-medium text-indigo-800 bg-indigo-300 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-400 transition duration-150 ease-in-out"
                @click="showModal = true">
                Create credentials
            </button>

            <x-slot name="title">Add credentials</x-slot>

            <x-slot name="content">
                <form id="credentials" action="{{ route('credentials.store') }}" method="POST">
                    @csrf

                    <label class="block mt-4">
                        <span class="text-gray-700">Username</span>
                        <input type="text" class="form-input mt-1 block w-full" id="username" name="username"/>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Password</span>
                        <input type="password" class="form-input mt-1 block w-full" id="password" name="password"/>
                    </label>

                </form>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" form="credentials"
                        class="px-4 py-2 text-sm font-medium text-green-800 bg-green-300 border border-transparent rounded-md hover:bg-green-200 focus:outline-none focus:border-green-700 focus:shadow-outline-indigo active:bg-green-400 transition duration-150 ease-in-out"
                        type="submit">Create
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
                            Username
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            JSESSIONID
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Bookings
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Poll
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse($credentials as $credential)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $credential->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $credential->session }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $credential->scheduled_bookings_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $credential->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">

                                <form method="POST" action="{{ route('credentials.destroy', $credential) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap truncate text-sm leading-5 text-gray-500">
                                No credentials added.
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
