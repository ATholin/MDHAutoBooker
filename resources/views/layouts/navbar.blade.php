<nav class="bg-gray-800" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="absolute inset-y-0 left-0 flex items-center md:hidden px-4">
                <!-- Mobile menu button-->
                <!-- Icon when menu is closed. -->
                <!-- Menu open: "hidden", Menu closed: "block" -->
                <svg class="block h-6 w-6 text-white cursor-pointer" stroke="currentColor" fill="none"
                     viewBox="0 0 24 24" @click="open = true" x-show="!open">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <!-- Icon when menu is open. -->
                <!-- Menu open: "block", Menu closed: "hidden" -->
                <svg x-cloak class="h-6 w-6 text-white cursor-pointer" stroke="currentColor" fill="none"
                     viewBox="0 0 24 24" @click="open = false" x-show="open">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1 flex items-center justify-center md:items-stretch md:justify-start">
                <div class="flex-shrink-0 flex items-center">
                    {{--            <img class="block lg:hidden h-8 w-auto" alt="Workflow logo" />--}}
                    {{--            <img class="hidden lg:block h-8 w-auto" alt="Workflow logo" />--}}
                    <a href="{{ route('home') }}" class="text-white">MDHAutoBooker</a>
                </div>
                <div class="hidden md:block sm:ml-6">
                    <div class="flex items-stretch space-x-3">
                        <a href="{{ route('home') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('home')) ? 'bg-gray-700' : '' }}">
                            <i class="fa fa-comments mr-1"></i>
                            Bookings
                        </a>
                        <a href="{{ route('user_bookings.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('user_bookings*')) ? 'bg-gray-700' : '' }}">
                            <i class="fa fa-comments mr-1"></i>
                            My Bookings
                        </a>
                        <a href="{{ route('scheduled_booking.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('scheduled*')) ? 'bg-gray-700' : '' }}">
                            <i class="fa fa-comments mr-1"></i>
                            Scheduled Bookings
                        </a>
                        <a href="{{ route('credentials.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('credentials*')) ? 'bg-gray-700' : '' }}">
                            <i class="fa fa-comments mr-1"></i>
                            Credentials
                        </a>
                        <a href="{{ route('friends.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('friends*')) ? 'bg-gray-700' : '' }}">
                            <i class="fa fa-comments mr-1"></i>
                            Friends
                        </a>
                    </div>
                </div>
            </div>
            <div
                class="absolute hidden md:block inset-y-0 right-0 flex items-center pr-2 md:static md:inset-auto md:ml-6 md:pr-0">

                <!-- Profile dropdown -->
                <div class="ml-3 flex items-center space-x-4">
                    <div>
                        @auth

                            <x-dropdown>
                                <span>
                                    <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->gravatar }}" alt=""/>
                                </span>

                                <x-slot name="dropdown">
                                    <div class="w-48 py-1 rounded-md bg-white shadow-xs" role="menu"
                                         aria-orientation="vertical" aria-labelledby="user-menu">
                                        <a href="{{ route('profile.index') }}"
                                           class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Profile</a>
                                        <a
                                            href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                        >
                                            Logout
                                        </a>

                                        @admin
                                        <div class="border-t border-gray-200 mt-2 pt-2 block">
                                            <a href="/telescope"
                                               class="block px-5 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Telescope</a>
                                            <a href="/admin"
                                               class="block px-5 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">Admin</a>
                                        </div>
                                        @endadmin
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium leading-5 text-gray-300 hover:text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Login</a>
                            <a href="{{ route('register') }}"
                               class="md:hidden ml-1 px-3 py-2 rounded-md text-sm font-medium leading-5 text-gray-300 hover:text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.
      Menu open: "block", Menu closed: "hidden"
    -->

    <div class="md:hidden" x-show="open" @click.away="open = false" x-cloak>
        <div class="px-2 pt-2 pb-3">
            <a href="{{ route('home') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('home')) ? 'bg-gray-900' : '' }}">
                <i class="fa fa-comments mr-1 text-gray-600"></i>
                Bookings
            </a>
            <a href="{{ route('user_bookings.index') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('user_bookings.index*')) ? 'bg-gray-900' : '' }}">
                <i class="fa fa-comments mr-1 text-gray-600"></i>
                My Bookings
            </a>
            <a href="{{ route('scheduled_booking.index') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('scheduled.index*')) ? 'bg-gray-900' : '' }}">
                <i class="fa fa-comments mr-1 text-gray-600"></i>
                Scheduled Bookings
            </a>
            <a href="{{ route('credentials.index') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('credentials.index*')) ? 'bg-gray-900' : '' }}">
                <i class="fa fa-comments mr-1 text-gray-600"></i>
                Credentials
            </a>
            <a href="{{ route('friends.index') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->routeIs('friends.index*')) ? 'bg-gray-900' : '' }}">
                <i class="fa fa-comments mr-1 text-gray-600"></i>
                Friends
            </a>


        </div>

        <div class="px-2 pt-2 pb-3 border-t border-gray-700">
            @auth
                {{--                <a href="{{ route('profile.index') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->is('profile*')) ? 'bg-gray-900' : '' }}">--}}
                {{--                    <i class="fa fa-user mr-1 text-gray-600"></i>--}}
                {{--                    {{ __('navbar.profile') }}--}}
                {{--                </a>--}}
                {{--                @admin--}}
                {{--                <a href="/admin" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">--}}
                {{--                    <i class="fa fa-lock mr-1 text-gray-600"></i>--}}
                {{--                    Admin--}}
                {{--                </a>--}}
                {{--                <a href="/telescope" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out">--}}
                {{--                    <i class="fa fa-circle mr-1 text-gray-600"></i>--}}
                {{--                    Telescope--}}
                {{--                </a>--}}
                {{--                @endadmin--}}
                {{--                <a href="{{ route('rankings.index') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->is('settings*')) ? 'bg-gray-900' : '' }}">--}}
                {{--                    <i class="fa fa-cog mr-1 text-gray-600 "></i>--}}
                {{--                    {{ __('navbar.settings') }}--}}
                {{--                </a>--}}
                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out"
                >
                    <i class="fa fa-sign-out-alt mr-1 text-gray-600 "></i>
                    Logout
                </a>


            @else
                <a href="{{ route('login') }}"
                   class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out {{ (request()->is('rankings*')) ? 'bg-gray-900' : '' }}">
                    <i class="fa fa-sign-in-alt mr-1 text-gray-600 "></i>
                    Login
                </a>
            @endauth
        </div>
    </div>

    <form id="logout-form" class="hidden" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
</nav>
