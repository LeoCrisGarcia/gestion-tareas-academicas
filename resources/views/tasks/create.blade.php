@extends('layouts.app')
@section('title', 'Nueva tarea')
@section('content')
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}" class="text-sm text-secondary/50 hover:text-secondary/80 mb-2 inline-block transition">&larr; Volver</a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Nueva tarea</h1>
    </div>

    <div class="max-w-lg">
        <div class="card-theme rounded-[var(--radius-hero)] p-8">
            <div class="relative z-10">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Materia</label>
                        <select name="subject_id" class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                            <option value="">Seleccionar materia</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id') <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Título</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                        @error('title') <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-secondary/70 mb-1.5">Descripción</label>
                        <textarea name="description" rows="3"
                                class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-secondary/70 mb-1.5">Fecha límite</label>
                            <input type="date" name="due_date" value="{{ old('due_date') }}"
                                class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                            @error('due_date') <p class="text-secondary/60 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary/70 mb-1.5">Prioridad</label>
                            <select name="priority" class="input-theme w-full rounded-[var(--radius-input)] px-4 py-2.5 text-sm">
                                <option value="baja" {{ old('priority') === 'baja' ? 'selected' : '' }}>Baja</option>
                                <option value="media" {{ old('priority') === 'media' ? 'selected' : '' }}>Media</option>
                                <option value="alta" {{ old('priority') === 'alta' ? 'selected' : '' }}>Alta</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-secondary/70 mb-1.5">Etiquetas</label>
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach ($tags as $tag)
                                    <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm cursor-pointer transition hover:opacity-80"
                                        style="background: {{ $tag->color }}20; color: {{ $tag->color }}; border: 1px solid {{ $tag->color }}40">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            class="rounded" style="border-color: var(--border-input); background: var(--bg-input); accent-color: var(--bg-accent);">
                                        {{ $tag->name }}
                                    </label>
                                @endforeach
                            </div>
                            <div x-data="{ newTag: '' }" class="flex gap-2">
                                <input type="text" x-model="newTag" placeholder="Nueva etiqueta..."
                                    @keydown.enter.prevent="fetch('{{ route('tags.store') }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ name: newTag, color: '#' + Math.floor(Math.random()*16777215).toString(16) })
                                    }).then(r => { if(r.ok) location.reload() })"
                                    class="input-theme flex-1 rounded-[var(--radius-input)] px-3 py-1.5 text-sm">
                                <button @click.prevent="fetch('{{ route('tags.store') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ name: newTag, color: '#' + Math.floor(Math.random()*16777215).toString(16) })
                                }).then(r => { if(r.ok) location.reload() })"
                                        class="btn-ghost rounded-[var(--radius-pill)] px-3 py-1.5 text-sm">
                                    + Agregar
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-accent w-full rounded-[var(--radius-pill)] px-4 py-2.5 text-sm">
                        Crear tarea
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
