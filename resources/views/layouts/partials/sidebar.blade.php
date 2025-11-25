{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<aside class="hidden md:block w-60 bg-gray-900 text-gray-200 min-h-screen shadow-lg">

    <div class="px-6 py-6 flex items-center gap-2 border-b border-gray-800">
        <x-application-mark class="h-8 w-8 text-indigo-400" />
        <span class="text-lg font-semibold">Menu</span>
    </div>

    <nav class="px-4 py-6 space-y-2">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition
                   {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12L12 3l9.75 9M4.5 9.75V21h15V9.75" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('eisenhower') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition
                   {{ request()->routeIs('eisenhower') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 7.5A2.25 2.25 0 0 1 6 5.25h5.25A2.25 2.25 0 0 1 13.5 7.5v9.75A2.25 2.25 0 0 1 11.25 19.5H6A2.25 2.25 0 0 1 3.75 17.25V7.5zm9.75 2.25h4.5A2.25 2.25 0 0 1 20.25 12v5.25A2.25 2.25 0 0 1 18 19.5h-4.5" />
            </svg>
            Matriz Eisenhower
        </a>

    </nav>
</aside>
