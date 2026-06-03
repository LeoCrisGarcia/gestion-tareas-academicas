@extends('layouts.app')
@section('title', 'Panel de control')
@section('content')
    @php
        $totalPendientes = $tasks->where('status', 'pendiente')->count();
        $totalCompletadas = $tasks->where('status', 'completada')->count();
        $totalEnProgreso = $tasks->where('status', 'en_progreso')->count();
        $totalTasksAll = max($totalTasks, 1);
        $completedPct = round(($totalCompletadas / $totalTasksAll) * 100);
        $completedDeg = round(($totalCompletadas / $totalTasksAll) * 360);
        $progressDeg = round(($totalEnProgreso / $totalTasksAll) * 360);
    @endphp

    <div class="card-glow rounded-[var(--radius-hero)] p-7 sm:p-9 mb-6">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-accent flex items-center justify-center text-xl font-bold text-on-accent shrink-0 ring-1 ring-[#9FFFE0]/20" style="box-shadow: 0 0 30px var(--glow-green);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 mb-0.5">
                        <h1 class="text-xl sm:text-2xl font-bold text-primary tracking-tight">
                            Buenos {{ now()->hour < 12 ? 'días' : (now()->hour < 18 ? 'tardes' : 'noches') }},
                        </h1>
                    </div>
                    <p class="text-sm text-secondary">
                        {{ auth()->user()->name }} · {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="flex gap-1 bg-surface rounded-[var(--radius-pill)] p-0.5 border border-subtle">
                    <a href="{{ route('dashboard') }}"
                       class="px-3.5 py-1.5 rounded-[var(--radius-pill)] text-xs font-semibold transition-all duration-200 {{ !$filter ? 'bg-accent text-on-accent' : 'text-secondary hover:text-primary' }}">Todas</a>
                    <a href="{{ route('dashboard', ['status' => 'pendiente']) }}"
                       class="px-3.5 py-1.5 rounded-[var(--radius-pill)] text-xs font-semibold transition-all duration-200 {{ $filter === 'pendiente' ? 'bg-hover text-primary' : 'text-secondary hover:text-primary' }}">Pendientes</a>
                    <a href="{{ route('dashboard', ['status' => 'completada']) }}"
                       class="px-3.5 py-1.5 rounded-[var(--radius-pill)] text-xs font-semibold transition-all duration-200 {{ $filter === 'completada' ? 'bg-accent text-on-accent' : 'text-secondary hover:text-primary' }}">Completadas</a>
                </div>
            </div>
        </div>
        <div class="relative z-10 flex flex-wrap gap-5 mt-5 pt-4 border-t border-subtle">
            <div class="flex items-center gap-2 text-xs text-secondary">
                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                {{ $totalCompletadas }} completadas
            </div>
            <div class="flex items-center gap-2 text-xs text-secondary">
                <span class="w-1.5 h-1.5 rounded-full" style="background: rgba(var(--text-secondary-rgb), 0.3);"></span>
                {{ $totalPendientes }} pendientes
            </div>
            <div class="flex items-center gap-2 text-xs text-secondary">
                <span class="w-1.5 h-1.5 rounded-full" style="background: rgba(var(--text-secondary-rgb), 0.15);"></span>
                {{ $stats['sin_fecha']->count() }} sin fecha
            </div>
            <div class="flex items-center gap-2 text-xs text-secondary ml-auto">
                <span class="text-[0.5rem] uppercase tracking-[0.12em] font-semibold">{{ now()->format('d M, Y') }}</span>
            </div>
        </div>
    </div>

    @php
        $metrics = [
            ['label' => 'Vencen hoy', 'count' => $stats['vencen_hoy']->count(), 'sub' => 'Requiere atención', 'color' => '#9FFFE0', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Vencidas',   'count' => $stats['vencidas']->count(),     'sub' => 'Atrasadas',       'color' => '#7EF3D1', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'],
            ['label' => 'Próximos 7 días', 'count' => $stats['proximos_7']->count(), 'sub' => 'En agenda', 'color' => '#0A3D33', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        @foreach ($metrics as $m)
        <div class="card-metric rounded-[var(--radius-metric)] p-6 sm:p-7">
            <div class="card-metric-accent rounded-[var(--radius-metric)]"
                 style="background: linear-gradient(90deg, {{ $m['color'] }}, {{ $m['color'] }}60, transparent);"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[0.6rem] font-semibold text-secondary uppercase tracking-[0.1em]">{{ $m['label'] }}</span>
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: {{ $m['color'] }}15; border: 1px solid {{ $m['color'] }}25;">
                        <svg class="w-4.5 h-4.5" style="color: {{ $m['color'] }}cc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $m['icon'] }}"/>
                        </svg>
                    </span>
                </div>
                <p class="text-4xl sm:text-5xl font-extrabold metric-number text-primary">{{ $m['count'] }}</p>
                <div class="flex items-center gap-1.5 mt-2.5">
                    @if ($m['count'] > 0)
                        <span class="text-xs font-medium" style="color: {{ $m['color'] }}cc">{{ $m['sub'] }}</span>
                        <span class="text-xs" style="color: rgba(var(--text-secondary-rgb), 0.4);">•</span>
                    @endif
                    <span class="text-[0.6rem] uppercase tracking-wider font-medium" style="color: rgba(var(--text-secondary-rgb), 0.5);">tareas</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-theme rounded-[var(--radius-card)] p-6 sm:p-7 mb-4">
        <div class="relative z-10 flex items-center justify-between mb-6">
            <div>
                <h2 class="text-sm font-bold text-primary/80 tracking-tight">Distribución por materia</h2>
                <p class="text-xs font-medium uppercase tracking-[0.08em] mt-0.5" style="color: rgba(var(--text-secondary-rgb), 0.7);">Carga académica por materia</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-surface text-xs font-semibold uppercase tracking-[0.1em] border border-subtle" style="color: rgba(var(--text-secondary-rgb), 0.6);">{{ $subjects->count() }} activos</span>
        </div>
        <div class="relative z-10 space-y-4">
            @foreach ($subjects as $i => $subject)
                @php
                    $subjectPct = round(($subject->tasks_count / $totalTasksAll) * 100);
                    $barPct = round(($subject->tasks_count / $maxTasks) * 100);
                @endphp
                <div class="group relative rounded-2xl transition-all duration-300 hover:bg-hover -mx-2 px-2 py-2">
                    <div class="flex items-center gap-4">
                        <span class="w-0.5 h-9 rounded-full shrink-0 transition-all duration-300"
                              style="background: {{ $subject->color }}; box-shadow: 0 0 8px {{ $subject->color }}40"></span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-sm font-semibold text-primary/60 group-hover:text-primary/80 transition-colors">{{ $subject->name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold text-primary/50 metric-number">{{ $subject->tasks_count }}</span>
                                    <span class="text-[0.55rem] font-semibold w-8 text-right" style="color: rgba(var(--text-secondary-rgb), 0.3);">{{ $subjectPct }}%</span>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="w-full bg-surface rounded-full h-2 overflow-hidden ring-1 ring-white/[0.02]">
                                    <div class="glow-bar h-2 rounded-full transition-all duration-700 ease-out relative"
                                         style="width: {{ $barPct }}%; background: linear-gradient(90deg, {{ $subject->color }}, {{ $subject->color }}aa, {{ $subject->color }}40)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card-glow rounded-[var(--radius-hero)] p-6 sm:p-8 mb-4">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-primary/80 tracking-tight">Rendimiento general</h2>
                    <p class="text-xs font-medium uppercase tracking-[0.08em] mt-0.5" style="color: rgba(var(--text-secondary-rgb), 0.7);">Métricas de rendimiento académico</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-center">
                <div class="lg:col-span-2 flex flex-col items-center gap-3">
                    <div class="conic-chart"
                         style="background: conic-gradient(#9FFFE0 0deg {{ $completedDeg }}deg, #7EF3D1 {{ $completedDeg }}deg {{ $completedDeg + $progressDeg }}deg, rgba(167, 180, 175, 0.08) {{ $completedDeg + $progressDeg }}deg 360deg)">
                        <div class="absolute inset-[14px] rounded-full bg-card flex items-center justify-center flex-col" style="box-shadow: inset 0 0 30px rgba(0,0,0,0.5); ring: 1px solid var(--border-subtle);">
                            <span class="text-3xl font-extrabold text-primary metric-number">{{ $completedPct }}%</span>
                        </div>
                    </div>
                    <span class="text-[0.5rem] text-muted/70 uppercase tracking-[0.15em] font-semibold -mt-1">Tasa de finalización</span>
                </div>
                <div class="lg:col-span-3 space-y-5">
                    <div class="space-y-3.5">
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color: rgba(var(--text-secondary-rgb), 0.6);">Pendientes</span>
                                <span class="font-semibold text-primary/60">{{ $totalPendientes }}</span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-2.5 overflow-hidden ring-1 ring-white/[0.02]">
                                <div class="h-2.5 rounded-full transition-all duration-700" style="background: rgba(var(--text-secondary-rgb), 0.15); width: {{ ($totalPendientes / $totalTasksAll) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color: rgba(126, 243, 209, 0.7);">En progreso</span>
                                <span class="font-semibold text-primary/60">{{ $totalEnProgreso }}</span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-2.5 overflow-hidden ring-1 ring-white/[0.02]">
                                <div class="h-2.5 rounded-full transition-all duration-700 glow-bar"
                                     style="width: {{ ($totalEnProgreso / $totalTasksAll) * 100 }}%; background: linear-gradient(90deg, #7EF3D1, #7EF3D160)"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color: rgba(159, 255, 224, 0.8);">Completadas</span>
                                <span class="font-semibold text-primary/60">{{ $totalCompletadas }}</span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-2.5 overflow-hidden ring-1 ring-white/[0.02]">
                                <div class="h-2.5 rounded-full transition-all duration-700 glow-bar"
                                     style="width: {{ ($totalCompletadas / $totalTasksAll) * 100 }}%; background: linear-gradient(90deg, #9FFFE0, #9FFFE060)"></div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-subtle pt-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-[0.5rem] font-semibold text-muted/70 uppercase tracking-[0.12em]">Total general</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs" style="color: rgba(var(--text-secondary-rgb), 0.4);"><span class="text-accent font-semibold">{{ $totalCompletadas }}</span> completadas</span>
                            <span class="text-lg font-extrabold text-primary metric-number">{{ $totalTasks }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($tasks->count())
        <div class="card-theme rounded-[var(--radius-card)] p-6 sm:p-7">
            <div class="relative z-10 flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-bold text-primary/80 tracking-tight">Tareas recientes</h2>
                    <p class="text-xs font-medium uppercase tracking-[0.08em] mt-0.5" style="color: rgba(var(--text-secondary-rgb), 0.7);">Últimas tareas registradas</p>
                </div>
                <span class="text-xs font-medium" style="color: rgba(var(--text-secondary-rgb), 0.4);">{{ $tasks->count() }} registros</span>
            </div>
            <div class="relative z-10 space-y-1">
                @foreach ($tasks as $task)
                    <div class="group relative rounded-2xl transition-all duration-300 hover:bg-hover {{ $task->due_date?->isPast() && $task->status !== 'completada' ? 'bg-hover' : '' }}">
                        <div class="flex items-center gap-3 py-3 px-3 -mx-3 rounded-2xl">
                            <span class="w-0.5 h-8 rounded-full shrink-0 transition-all duration-300"
                                  style="background: {{ $task->subject->color }}; box-shadow: 0 0 6px {{ $task->subject->color }}30"></span>
                            <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-12 items-center gap-2 sm:gap-4">
                                <div class="sm:col-span-4 flex items-center gap-2">
                                    <p class="text-sm font-medium text-primary/70 truncate">{{ $task->title }}</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <span class="px-2 py-0.5 rounded-full text-[0.5rem] font-semibold uppercase tracking-wider text-primary/70"
                                          style="background: {{ $task->subject->color }}40">
                                        {{ $task->subject->name }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2 text-xs" style="color: rgba(var(--text-secondary-rgb), 0.4);">
                                    {{ $task->due_date?->format('d/m/Y') ?: '—' }}
                                    @if ($task->due_date?->isPast() && $task->status !== 'completada')
                                        <span style="color: rgba(var(--text-secondary-rgb), 0.5);" class="ml-1">atrasada</span>
                                    @endif
                                </div>
                                <div class="sm:col-span-2 sm:text-right">
                                    @php
                                        $sbadges = ['pendiente' => 'badge-pending', 'en_progreso' => 'badge-progress', 'completada' => 'badge-done'];
                                    @endphp
                                    <span class="badge {{ $sbadges[$task->status] }}">
                                        {{ $task->status === 'en_progreso' ? 'En progreso' : ucfirst($task->status) }}
                                    </span>
                                </div>
                                <div class="sm:col-span-1 flex justify-end">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $task->status === 'completada' ? 'bg-accent' : ($task->due_date?->isPast() ? 'bg-secondary/30' : 'bg-secondary/15') }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card-theme rounded-[var(--radius-card)] p-14 text-center">
            <div class="relative z-10">
                <p class="text-secondary/50 text-base">No hay tareas que mostrar.</p>
                <a href="{{ route('tasks.create') }}" class="inline-block mt-4 text-accent font-medium text-sm hover:text-on-accent transition">Crea tu primera tarea</a>
            </div>
        </div>
    @endif
@endsection
