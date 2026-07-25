@php
    $agent = auth()->user()?->agent;
    $isAdmin = auth()->user()?->isAdmin() ?? false;
    $settingsRoute = $isAdmin ? 'admin.settings.edit' : 'agent.profile.edit';
    $initials = $agent
        ? strtoupper(substr($agent->f_name, 0, 1) . substr($agent->l_name, 0, 1))
        : strtoupper(substr(auth()->user()->name ?? 'A', 0, 2));
@endphp

<header class="flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">
    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 lg:hidden">
        <x-agent.icon name="menu" class="h-6 w-6" />
    </button>

    <div class="min-w-0 flex-1">
        <div class="relative max-w-md">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <x-agent.icon name="search" class="h-4 w-4" />
            </span>
            <input
                type="text"
                placeholder="Search properties, clients..."
                class="w-full rounded-lg border-0 bg-gray-100 py-2 pl-9 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500"
            >
        </div>
    </div>

    <button class="relative text-gray-400 hover:text-gray-600">
        <x-agent.icon name="bell" class="h-6 w-6" />
        @if(($unreadNotifications ?? 0) > 0)
            <span class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-red-500"></span>
        @endif
    </button>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.outside="open = false" class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">
            {{ $initials }}
        </button>
        <div
            x-show="open"
            x-cloak
            x-transition
            class="absolute right-0 z-50 mt-2 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
        >
            <div class="border-b border-gray-100 px-4 py-2">
                <p class="truncate text-sm font-medium text-gray-900">{{ $agent ? $agent->f_name.' '.$agent->l_name : auth()->user()->name }}</p>
                <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route($settingsRoute) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">Log out</button>
            </form>
        </div>
    </div>
</header>
