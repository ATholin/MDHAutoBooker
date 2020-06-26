@extends('layouts.base')

@section('body')
    @include('layouts.navbar')
    <div class="max-w-5xl px-4 xl:px-0 mx-auto py-16">
        @yield('content')
    </div>
@endsection
