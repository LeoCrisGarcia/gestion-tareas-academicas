@extends('layouts.app')
@section('title', 'Materias')
@section('content')
    @php
        $hasTasksRelation = $subjects->isNotEmpty() && isset($subjects->first()->tasks_count);
    @endphp

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Materias</h1>
            <p class="text-secondary/50 mt-1">{{ $subjects->count() }} materias</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex gap-1 bg-surface rounded-[var(--radius-pill)] p-0.5 border border-subtle"
                 x-data="{ view: localStorage.getItem('subjectsView') || 'grid' }"
                 x-init="$watch('view', v => localStorage.setItem('subjectsView', v))">
                <button @click="view = 'grid'"
                        class="px-3 py-1.5 rounded-[var(--radius-pill)] text-xs font-semibold transition-all duration-200"
                        :class="view === 'grid' ? 'bg-accent text-on-accent' : 'text-secondary hover:text-primary'">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button @click="view = 'list'"
                        class="px-3 py-1.5 rounded-[var(--radius-pill)] text-xs font-semibold transition-all duration-200"
                        :class="view === 'list' ? 'bg-accent text-on-accent' : 'text-secondary hover:text-primary'">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            <a href="{{ route('subjects.create') }}"
               class="btn-accent rounded-[var(--radius-pill)] px-5 py-2.5 text-sm font-semibold">
                + Nueva materia
            </a>
        </div>
    </div>

    @if ($subjects->count())
        <div x-data="{ view: localStorage.getItem('subjectsView') || 'grid' }"
             x-init="$watch('view', v => localStorage.setItem('subjectsView', v))">
            <div x-show="view === 'grid'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($subjects as $subject)
                <div class="card-theme rounded-[var(--radius-card)] p-5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-4 h-4 rounded-full inline-block" style="background: {{ $subject->color }}; box-shadow: 0 0 12px {{ $subject->color }}40"></span>
                            <h2 class="text-lg font-semibold text-primary/80">{{ $subject->name }}</h2>
                        </div>
                        @if ($hasTasksRelation)
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-muted">Tareas</span>
                                <span class="text-primary/60 font-semibold">{{ $subject->tasks_count }}</span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full transition-all duration-700"
                                     style="width: min({{ $subject->tasks_count / max($subjects->max('tasks_count'), 1) * 100 }}%, 100%); background: {{ $subject->color }}"></div>
                            </div>
                        </div>
                        @endif
                        <div class="flex gap-2">
                            <a href="{{ route('subjects.edit', $subject) }}"
                               class="text-sm text-muted hover:text-primary px-3 py-1.5 rounded-lg hover:bg-hover transition">Editar</a>
                            <div x-data="{ open: false }" class="inline">
                                <button @click="open = true"
                                        class="text-sm text-muted hover:text-secondary px-3 py-1.5 rounded-lg hover:bg-hover transition">Eliminar</button>
                                <div x-show="open" x-cloak
                                     class="fixed inset-0 bg-overlay backdrop-blur-sm flex items-center justify-center z-50"
                                     @click.self="open = false">
                                    <div class="card-theme rounded-[var(--radius-card)] p-6 max-w-sm w-full mx-4" style="box-shadow: var(--shadow-dark);">
                                        <div class="relative z-10">
                                            <h3 class="text-lg font-semibold text-primary mb-2">Eliminar materia</h3>
                                            <p class="text-secondary/50 mb-6">¿Eliminar "{{ $subject->name }}"?</p>
                                            <div class="flex justify-end gap-2">
                                                <button @click="open = false"
                                                        class="btn-ghost rounded-[var(--radius-pill)] px-5 py-2 text-sm font-medium">Cancelar</button>
                                                <form method="POST" action="{{ route('subjects.destroy', $subject) }}">
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
                    </div>
                </div>
                @endforeach
            </div>

            <div x-show="view === 'list'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="card-theme rounded-[var(--radius-card)] overflow-hidden">
                <div class="relative z-10">
                    @foreach ($subjects as $i => $subject)
                    <div class="flex items-center gap-4 px-5 py-4 transition-colors hover:bg-hover {{ $i < count($subjects) - 1 ? 'border-b border-subtle' : '' }}">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $subject->color }}; box-shadow: 0 0 8px {{ $subject->color }}40"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-primary/70">{{ $subject->name }}</p>
                            @if ($hasTasksRelation)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs" style="color: rgba(var(--text-secondary-rgb), 0.3);">{{ $subject->tasks_count }} tareas</span>
                                <div class="flex-1 max-w-32 bg-surface rounded-full h-1 overflow-hidden">
                                    <div class="h-1 rounded-full transition-all duration-700"
                                         style="width: min({{ $subject->tasks_count / max($subjects->max('tasks_count'), 1) * 100 }}%, 100%); background: {{ $subject->color }}"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('subjects.edit', $subject) }}"
                               class="text-xs text-muted hover:text-primary px-2.5 py-1.5 rounded-lg hover:bg-hover transition">Editar</a>
                            <div x-data="{ open: false }" class="inline">
                                <button @click="open = true"
                                        class="text-xs text-muted hover:text-secondary px-2.5 py-1.5 rounded-lg hover:bg-hover transition">Eliminar</button>
                                <div x-show="open" x-cloak
                                     class="fixed inset-0 bg-overlay backdrop-blur-sm flex items-center justify-center z-50"
                                     @click.self="open = false">
                                    <div class="card-theme rounded-[var(--radius-card)] p-6 max-w-sm w-full mx-4" style="box-shadow: var(--shadow-dark);">
                                        <div class="relative z-10">
                                            <h3 class="text-lg font-semibold text-primary mb-2">Eliminar materia</h3>
                                            <p class="text-secondary/50 mb-6">¿Eliminar "{{ $subject->name }}"?</p>
                                            <div class="flex justify-end gap-2">
                                                <button @click="open = false"
                                                        class="btn-ghost rounded-[var(--radius-pill)] px-5 py-2 text-sm font-medium">Cancelar</button>
                                                <form method="POST" action="{{ route('subjects.destroy', $subject) }}">
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
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card-theme rounded-[var(--radius-card)] p-12 text-center">
            <div class="relative z-10">
                <p class="text-secondary/50 text-lg">No tenés materias todavía.</p>
                <a href="{{ route('subjects.create') }}" class="inline-block mt-4 text-accent font-medium hover:text-on-accent transition">Creá tu primera materia</a>
            </div>
        </div>
    @endif
@endsection
