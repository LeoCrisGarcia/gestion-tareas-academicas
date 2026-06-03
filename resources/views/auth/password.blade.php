@extends('layouts.app')
@section('title', 'Cambiar contraseña')
@section('content')
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-secondary/50 hover:text-secondary/80 mb-2 inline-block transition">&larr; Volver al panel</a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Cambiar contraseña</h1>
    </div>

    <div class="max-w-lg">
        <div class="card-theme rounded-[var(--radius-hero)] p-8">
            <div class="relative z-10">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Contraseña actual</label>
                        <input type="password" name="current_password"
                               class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm @error('current_password') input-error @enderror">
                        @error('current_password') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Nueva contraseña</label>
                        <input type="password" name="password"
                               class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm @error('password') input-error @enderror">
                        @error('password') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation"
                               class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm @error('password_confirmation') input-error @enderror">
                        @error('password_confirmation') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-accent w-full rounded-[var(--radius-pill)] px-4 py-2.5 text-sm">
                        Actualizar contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
