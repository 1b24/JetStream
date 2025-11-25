{{-- resources/views/layouts/partials/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('eisenhower') }}" :active="request()->routeIs('eisenhower')">
                        {{ __('Matriz Eisenhower') }}
                    </x-nav-link>
                </div>

            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ml-3 relative">
                        @include('layouts.partials.team-dropdown')
                    </div>
                @endif

                <!-- User Dropdown -->
                <div class="ml-3 relative">
                    @include('layouts.partials.user-dropdown')
                </div>

            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('eisenhower') }}" :active="request()->routeIs('eisenhower')">
                {{ __('Matriz Eisenhower') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings -->
        @include('layouts.partials.responsive-user-menu')
    </div>

</nav>
