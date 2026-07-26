@php
    $agent = auth()->user()?->agent;
    $isAdmin = auth()->user()?->isAdmin() ?? false;
    $rp = $isAdmin ? 'admin' : 'agent'; // route-name prefix for role-segmented controllers

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
    class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-slate-900 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
>
    <div class="flex h-16 shrink-0 items-center gap-2 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white">E</span>
            <span class="text-base font-bold text-white">Estate</span>
        </a>
    </div>

    <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 pb-4">
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
                $navItem('Properties', $rp.'.properties.index', 'building', [$rp.'.properties.index', $rp.'.properties.show', $rp.'.properties.edit', $rp.'.properties.filter', $rp.'.properties.photos.*']),
                $navItem('Add Property', $rp.'.properties.create', 'plus'),
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
            @php($item = $navItem('Clients', $rp.'.clients.index', 'users', [$rp.'.clients.*']))
            <li>
                <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            </li>
        </ul>

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Scheduling</p>
        <ul class="space-y-1">
            @foreach ([
                $navItem('Calendar', 'calendar.index', 'calendar', ['calendar.*']),
                $navItem('Appointments', 'appointments.index', 'clock', ['appointments.*']),
                $navItem('Availability', 'availability.index', 'check-square', ['availability.*']),
            ] as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['isActive'] ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition">
                        <x-agent.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
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

        <p class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Account</p>
        <ul class="space-y-1">
            @php($item = $isAdmin ? $navItem('Settings', 'admin.settings.edit', 'settings') : $navItem('Settings', 'agent.profile.edit', 'settings', ['agent.profile.*']))
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