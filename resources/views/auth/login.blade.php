<!DOCTYPE html>
<html lang="es" x-data="theme()" :data-theme="mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center antialiased font-sans" style="background: var(--bg-body); color: var(--text-primary);">
    <div class="w-full max-w-sm mx-4">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold tracking-tight text-primary">TaskFlow</h1>
            <p class="text-secondary/50 mt-2 text-sm">Iniciá sesión para continuar</p>
        </div>

        <div class="card-theme rounded-[var(--radius-hero)] p-8">
            <div class="relative z-10">
                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                               class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                        @error('email')
                            <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Contraseña</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" autocomplete="current-password"
                                   class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 pr-10 text-sm">
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary/30 hover:text-secondary/60">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                               class="rounded" style="border-color: var(--border-input); background: var(--bg-input); accent-color: var(--bg-accent);">
                        <label for="remember" class="ml-2 text-sm text-secondary/60">Recordarme</label>
                    </div>

                    <button type="submit" class="btn-accent w-full rounded-[var(--radius-pill)] px-4 py-2.5 text-sm">
                        Iniciar sesión
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-secondary/50">
                    ¿No tenés cuenta?
                    <a href="/register" class="text-accent font-medium hover:text-on-accent transition">Registrate</a>
                </p>
            </div>
        </div>
    </div>

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
