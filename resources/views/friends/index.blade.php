@extends('layouts.app')

@section('content')
    <div class="flex justify-between">
        @include('partials.h1', ['text' => 'Friends'])
        <x-modal x-cloak>
            <button
                class="px-4 py-2 text-sm font-medium text-indigo-800 bg-indigo-300 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-400 transition duration-150 ease-in-out"
                @click="showModal = true">
                Add friend
            </button>

            <x-slot name="title">Add friend</x-slot>

            <x-slot name="content">
                <form id="friends" action="{{ route('friends.store') }}" method="POST">
                    @csrf

                    <label class="block mt-4">
                        <span class="text-gray-700">Name</span>
                        <input type="text" class="form-input mt-1 block w-full" id="name" name="name"/>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">MDH Username</span>
                        <input type="text" class="form-input mt-1 block w-full" id="mdh_username" name="mdh_username"/>
                    </label>

                    <label class="block mt-4">
                        <span class="text-gray-700">Color</span>
                        <input type="color" class="form-input mt-1 block w-full" id="color" name="color"/>
                    </label>

                </form>
            </x-slot>

            <x-slot name="footer">
                <button type="submit" form="friends"
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
                            Name
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            MDH Username
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Color
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse($friends as $friend)
                        <tr>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $friend->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                {{ $friend->mdh_username }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500 space-x-2">
                                <span class="rounded-full h-3 w-3 inline-block"
                                      style="background-color: {{ $friend->color }}"></span>
                                <span>{{ $friend->color }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">

                                <form method="POST" action="{{ route('friends.destroy', $friend) }}">
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
                                No friends added.
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
