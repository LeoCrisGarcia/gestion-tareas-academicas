@extends('layouts.app')
@section('title', 'Editar materia')
@section('content')
    <div class="mb-8">
        <a href="{{ route('subjects.index') }}" class="text-sm text-secondary/50 hover:text-secondary/80 mb-2 inline-block transition">&larr; Volver</a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Editar materia</h1>
    </div>

    <div class="max-w-lg">
        <div class="card-theme rounded-[var(--radius-hero)] p-8">
            <div class="relative z-10">
                <form method="POST" action="{{ route('subjects.update', $subject) }}">
                    @csrf @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $subject->name) }}"
                               class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                        @error('name') <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Color</label>
                        <input type="color" name="color" value="{{ old('color', $subject->color) }}"
                               class="w-full h-10 rounded-[var(--radius-input)]" style="border: 1px solid var(--border-input); background: var(--bg-input); cursor: pointer;">
                        @error('color') <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-accent w-full rounded-[var(--radius-pill)] px-4 py-2.5 text-sm">
                        Actualizar materia
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
