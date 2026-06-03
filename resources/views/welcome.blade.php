<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="theme()" :data-theme="mode">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskFlow</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="apple-touch-icon" href="/apple-touch-icon.svg">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex items-center justify-center antialiased font-sans" style="background: var(--bg-body); color: var(--text-primary);">
        <div class="text-center">
            <h1 class="text-6xl font-extrabold tracking-tight text-primary mb-4">TaskFlow</h1>
            <p class="text-secondary/50 text-lg mb-8">Gestión de tareas académicas</p>
            <div class="flex gap-4 justify-center">
                <a href="/login" class="btn-accent rounded-[var(--radius-pill)] px-8 py-3 text-sm font-semibold">
                    Iniciar sesión
                </a>
                <a href="/register" class="btn-ghost rounded-[var(--radius-pill)] px-8 py-3 text-sm font-medium">
                    Registrarse
                </a>
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
