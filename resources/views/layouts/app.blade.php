<!DOCTYPE html>
<html lang="es" x-data="theme()" :data-theme="mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskFlow')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased font-sans text-primary" style="background: var(--bg-body);">
    <nav class="nav-theme sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between" x-data="{ mobileOpen: false }">
            <a href="/dashboard" class="text-lg font-bold tracking-tight text-primary">
                <span class="hidden sm:inline">TaskFlow</span>
                <span class="sm:hidden">TF</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                @php
                    $links = [
                        'dashboard' => ['route' => 'dashboard', 'label' => 'Panel'],
                        'subjects' => ['route' => 'subjects.index', 'label' => 'Materias'],
                        'tasks' => ['route' => 'tasks.index', 'label' => 'Tareas'],
                        'calendar' => ['route' => 'calendar.index', 'label' => 'Calendario'],
                    ];
                @endphp
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                       {{ request()->routeIs($link['route']) ? 'nav-link-active' : 'nav-link' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                {{-- Theme toggle --}}
                <button @click="toggle()"
                        class="p-2 rounded-full nav-link hover:bg-surface transition-all duration-200"
                        :title="mode === 'dark' ? 'Activar modo claro' : 'Activar modo oscuro'">
                    <svg x-show="mode === 'dark'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="mode === 'light'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <button class="md:hidden p-2 nav-link hover:bg-surface rounded-full" @click="mobileOpen = !mobileOpen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 nav-link transition">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-accent text-on-accent">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 bg-white rounded-2xl py-1.5 w-56 shadow-lg ring-1 ring-[#0A3D33]/10 border border-[#0A3D33]/5">
                        <p class="px-4 py-2 text-sm text-[#0A3D33]/60 border-b border-[#0A3D33]/5 font-medium">{{ auth()->user()->email }}</p>
                        <a href="{{ route('password.edit') }}" class="block px-4 py-2 text-sm text-[#0A3D33]/70 hover:text-[#0A3D33] hover:bg-[#9FFFE0]/10 transition">Cambiar contraseña</a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-[#0A3D33]/70 hover:text-[#0A3D33] hover:bg-[#9FFFE0]/10 transition">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak @click.outside="mobileOpen = false" class="md:hidden nav-mobile">
            <div class="flex flex-col p-3 gap-1">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="px-4 py-2.5 rounded-xl text-sm font-medium transition
                       {{ request()->routeIs($link['route']) ? 'nav-link-active' : 'nav-link' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
            <div class="bg-accent/80 text-on-accent px-5 py-3 rounded-xl text-sm font-medium border border-accent/50 shadow-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
            <div class="px-5 py-3 rounded-xl text-sm font-medium border"
                 style="background: var(--bg-accent); color: var(--text-on-accent); border-color: var(--border-card);">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('theme', () => ({
                mode: localStorage.getItem('taskflow-theme') || 'dark',
                init() {
                    this.$watch('mode', val => localStorage.setItem('taskflow-theme', val));
                },
                toggle() {
                    this.mode = this.mode === 'dark' ? 'light' : 'dark';
                }
            }));
        });
    </script>
</body>
</html>
