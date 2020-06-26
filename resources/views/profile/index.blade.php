@extends('layouts.app')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Profile
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                Account details and information
            </p>
        </div>
        <div>
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Username
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Email address
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->email }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Scheduled bookings
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->bookings()->count() }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{--<x-modal class="mt-4">
        <button class="px-4 py-2 text-sm font-medium text-red-800 bg-red-300 border border-transparent rounded-md hover:bg-red-200 focus:outline-none focus:border-red-700 focus:shadow-outline-indigo active:bg-red-400 transition duration-150 ease-in-out" @click="showModal = true">{{ __('profile/index.delete_account') }}</button>

        <x-slot name="title">{{ __('profile/index.delete_account') }}</x-slot>
        <x-slot name="subtitle">{{ __('profile/index.modal_subtitle') }}</x-slot>

        <x-slot name="footer">
            <form method="POST">
                @method('DELETE')
                @csrf
                <button class="px-4 py-2 text-sm font-medium text-red-800 bg-red-300 border border-transparent rounded-md hover:bg-red-200 focus:outline-none focus:border-red-700 focus:shadow-outline-indigo active:bg-red-400 transition duration-150 ease-in-out" type="submit">{{ __('profile/index.delete_account') }}</button>
            </form>
            <button class="ml-1 px-4 py-2 text-sm font-medium text-gray-600 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus:shadow-outline-indigo active:bg-gray-400 transition duration-150 ease-in-out" @click="showModal = false">{{ __('modal.close') }}</button>
        </x-slot>
    </x-modal>--}}
@endsection
