@php
    $agent = auth()->user()?->agent;
    $isAdmin = auth()->user()?->isAdmin() ?? false;
    $unreadNotifications = $agent?->notifications()->unread()->count() ?? 0;

    $navItem = function (string $label, ?string $routeName, string $icon, array $activePatterns = []) {
        $patterns = $activePatterns ?: [$routeName];
        $isActive = collect($patterns)->filter()->contains(fn ($p) => request()->routeIs($p));
        $href = $routeName && \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#';

        return compact('label', 'href', 'icon', 'isActive');
    };
@endphp

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 flex-col overflow-hidden bg-slate-900 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
>
    <div class="flex h-16 shrink-0 items-center gap-2 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white">E</span>
            <span class="text-base font-bold text-white">Estate</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 pb-4 [scrollbar-width:thin] [scrollbar-color:theme(colors.slate.700)_transparent] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-700 hover:[&::-webkit-scrollbar-thumb]:bg-slate-600">
        <ul class="space-y-1">
            @php($item = $navItem('Dashboard', 'dashboard', 'home'))
            <li>
                <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            </li>
        </ul>

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Properties</p>
        <ul class="space-y-1">
            @foreach ([
                $navItem('Properties', 'properties.index', 'building', ['properties.index', 'properties.show', 'properties.edit', 'properties.filter', 'properties.photos.*']),
                $navItem('Add Property', 'properties.create', 'plus'),
            ] as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">People</p>
        <ul class="space-y-1">
            @php($item = $navItem('Clients', 'clients.index', 'users'))
            <li>
                <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            </li>
        </ul>

        @if ($isAdmin)
            <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Admin</p>
            <ul class="space-y-1">
                @php($item = $navItem('Agents', 'admin.agents.index', 'badge', ['admin.agents.*']))
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            </ul>
        @endif

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Discover</p>
        <ul class="space-y-1">
            @foreach ([
                $navItem('Search', 'search.index', 'search'),
                $navItem('Saved Searches', 'saved-searches.index', 'bookmark'),
            ] as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Scheduling</p>
        <ul class="space-y-1">
            @foreach ([
                $navItem('Calendar', 'appointments.index', 'calendar'),
                $navItem('Appointments', 'appointments.index', 'clock'),
                ...(! $isAdmin ? [$navItem('Availability', 'availability.index', 'check-square')] : []),
            ] as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Account</p>
        <ul class="space-y-1">
            @php($item = $navItem('Notifications', 'notifications.index', 'bell'))
            <li>
                <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <span class="flex items-center gap-3">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </span>
                    @if ($unreadNotifications > 0)
                        <span class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-600 px-1 text-xs font-semibold text-white">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </a>
            </li>
            @php($item = $navItem('Settings', 'agent.edit', 'settings'))
            <li>
                <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            </li>
        </ul>
    </nav>

    <div class="shrink-0 border-t border-slate-800 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white">
                <x-agent.icon name="log-out" class="h-5 w-5 shrink-0" />
                Log out
            </button>
        </form>
    </div>
</aside>

<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
></div>
