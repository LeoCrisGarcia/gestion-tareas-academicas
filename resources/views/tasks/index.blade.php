@extends('layouts.app')
@section('title', 'Tareas')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Tareas</h1>
        <p class="text-secondary/50 mt-1">{{ $tasks->count() }} tareas</p>
    </div>
    <a href="{{ route('tasks.create') }}"
        class="btn-accent rounded-[var(--radius-pill)] px-5 py-2.5 text-sm font-semibold">
        + Nueva tarea
    </a>
</div>

<form method="GET" class="card-theme rounded-[var(--radius-card)] p-4 mb-6">
    <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Buscar por título..."
            value="{{ request('search') }}"
            class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
        <select name="subject_id" class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
            <option value="">Todas las materias</option>
            @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
        <select name="status" class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="en_progreso" {{ request('status') === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
            <option value="completada" {{ request('status') === 'completada' ? 'selected' : '' }}>Completada</option>
        </select>
        <select name="priority" class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
            <option value="">Todas las prioridades</option>
            <option value="baja" {{ request('priority') === 'baja' ? 'selected' : '' }}>Baja</option>
            <option value="media" {{ request('priority') === 'media' ? 'selected' : '' }}>Media</option>
            <option value="alta" {{ request('priority') === 'alta' ? 'selected' : '' }}>Alta</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
            class="input-theme rounded-[var(--radius-input)] px-4 py-2 text-sm">
        <div class="flex gap-2 items-end">
            <button type="submit"
                class="btn-accent rounded-[var(--radius-pill)] px-5 py-2 text-sm font-semibold">Filtrar</button>
            <a href="{{ route('tasks.index') }}"
                class="btn-ghost rounded-[var(--radius-pill)] px-5 py-2 text-sm font-medium">Limpiar</a>
        </div>
    </div>
</form>

@if ($tasks->count())
<div class="card-theme rounded-[var(--radius-card)] overflow-hidden">
    <div class="relative z-10 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[0.55rem] font-semibold uppercase tracking-[0.12em]" style="color: rgba(var(--text-secondary-rgb), 0.3);">
                <tr>
                    <th class="px-5 py-3.5 font-medium">Título</th>
                    <th class="px-5 py-3.5 font-medium">Materia</th>
                    <th class="px-5 py-3.5 font-medium">Vence</th>
                    <th class="px-5 py-3.5 font-medium">Prioridad</th>
                    <th class="px-5 py-3.5 font-medium">Etiquetas</th>
                    <th class="px-5 py-3.5 font-medium">Estado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-subtle);">
                @foreach ($tasks as $task)
                <tr class="transition-colors hover:bg-hover {{ $task->due_date?->isPast() && $task->status !== 'completada' ? 'bg-hover' : '' }}">
                    <td class="px-5 py-3.5 font-medium text-primary/70">{{ $task->title }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[0.55rem] font-semibold text-primary/80" style="background: {{ $task->subject->color }}50">
                            {{ $task->subject->name }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5" style="color: rgba(var(--text-secondary-rgb), 0.4);">{{ $task->due_date?->format('d/m/Y') ?: '—' }}</td>
                    <td class="px-5 py-3.5">
                        @php
                            $pbadges = ['baja' => 'opacity-30', 'media' => 'opacity-50', 'alta' => 'opacity-70'];
                        @endphp
                        <span class="text-xs font-medium text-secondary {{ $pbadges[$task->priority] }}">{{ ucfirst($task->priority) }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex flex-wrap gap-1">
                            @foreach ($task->tags as $tag)
                            <span class="px-2 py-0.5 rounded-full text-[0.55rem] font-medium"
                                style="background: {{ $tag->color }}20; color: {{ $tag->color }}">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $sbadges = ['pendiente' => 'badge-pending', 'en_progreso' => 'badge-progress', 'completada' => 'badge-done'];
                        @endphp
                        <span class="badge {{ $sbadges[$task->status] }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            @if ($task->status !== 'completada')
                                <a href="{{ route('tasks.edit', $task) }}" class="text-xs text-muted hover:text-primary px-2.5 py-1 rounded-lg hover:bg-hover transition">Editar</a>
                            @endif
                            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs {{ $task->status === 'completada' ? 'text-muted' : 'text-accent' }} px-2.5 py-1 rounded-lg hover:bg-hover transition">
                                    {{ $task->status === 'completada' ? 'Reabrir' : 'Completar' }}
                                </button>
                            </form>
                            <div x-data="{ open: false }" class="inline">
                                <button @click="open = true" class="text-xs text-muted hover:text-secondary px-2.5 py-1 rounded-lg hover:bg-hover transition">Eliminar</button>
                                <div x-show="open" x-cloak
                                    class="fixed inset-0 bg-overlay backdrop-blur-sm flex items-center justify-center z-50"
                                    @click.self="open = false">
                                    <div class="card-theme rounded-[var(--radius-card)] p-6 max-w-sm w-full mx-4" style="box-shadow: var(--shadow-dark);">
                                        <div class="relative z-10">
                                            <h3 class="text-lg font-semibold text-primary mb-2">Eliminar tarea</h3>
                                            <p class="text-secondary/50 mb-6">¿Eliminar "{{ $task->title }}"?</p>
                                            <div class="flex justify-end gap-2">
                                                <button @click="open = false"
                                                    class="btn-ghost rounded-[var(--radius-pill)] px-5 py-2 text-sm font-medium">Cancelar</button>
                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="btn-accent rounded-[var(--radius-pill)] px-5 py-2 text-sm font-semibold">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card-theme rounded-[var(--radius-card)] p-12 text-center">
    <div class="relative z-10">
        <p class="text-secondary/50 text-lg">No hay tareas que mostrar.</p>
        <a href="{{ route('tasks.create') }}" class="inline-block mt-4 text-accent font-medium hover:text-on-accent transition">Creá tu primera tarea</a>
    </div>
</div>
@endif
@endsection
