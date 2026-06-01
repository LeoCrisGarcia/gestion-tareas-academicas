@extends('layouts.app')
@section('title', 'Calendario')
@section('content')
    @php
        $start = $startOfWeek;
        $end = $endOfWeek;
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-primary">Calendario</h1>
        <form method="GET" class="flex items-center gap-2">
            <select name="month" onchange="this.form.submit()"
                    class="input-theme rounded-[var(--radius-input)] px-3 py-2 text-sm">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()"
                    class="input-theme rounded-[var(--radius-input)] px-3 py-2 text-sm">
                @foreach (range(now()->year - 2, now()->year + 2) as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <a href="?" class="text-sm text-accent font-medium hover:text-on-accent transition px-3 py-2">Hoy</a>
        </form>
    </div>

    <div class="card-theme rounded-[var(--radius-card)] overflow-hidden">
        <div class="relative z-10">
            <div class="grid grid-cols-7 text-center text-[0.55rem] font-semibold uppercase tracking-wider border-b" style="color: rgba(var(--text-secondary-rgb), 0.3); border-color: var(--border-subtle);">
                <div class="py-3 bg-hover">Lun</div><div class="py-3 bg-hover">Mar</div><div class="py-3 bg-hover">Mié</div>
                <div class="py-3 bg-hover">Jue</div><div class="py-3 bg-hover">Vie</div><div class="py-3 bg-hover">Sáb</div><div class="py-3 bg-hover">Dom</div>
            </div>
            <div class="grid grid-cols-7">
                @foreach (range(0, $start->diffInDays($end)) as $i)
                    @php
                        $day = $start->copy()->addDays($i);
                        $isCurrentMonth = $day->month === $month;
                        $isToday = $day->isToday();
                        $dayKey = $day->format('Y-m-d');
                        $dayTasks = $tasks[$dayKey] ?? collect();
                    @endphp
                    <div class="min-h-28 p-2 border-b border-r relative {{ $isCurrentMonth ? 'bg-transparent' : 'bg-hover' }} {{ $isToday ? 'bg-accent/20' : '' }}"
                         style="border-color: var(--border-subtle);"
                         x-data="{ show: false }"
                         @mouseenter="show = true" @mouseleave="show = false">
                        <p class="text-sm mb-1 {{ $isToday ? 'font-bold text-accent' : ($isCurrentMonth ? 'text-primary/60' : 'text-secondary/20') }}">{{ $day->day }}</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($dayTasks->take(3) as $task)
                                <span class="w-2 h-2 rounded-full inline-block" style="background: {{ $task->subject->color }}; box-shadow: 0 0 6px {{ $task->subject->color }}40"></span>
                            @endforeach
                            @if ($dayTasks->count() > 3)
                                <span class="text-xs ml-0.5" style="color: rgba(var(--text-secondary-rgb), 0.3);">+{{ $dayTasks->count() - 3 }}</span>
                            @endif
                        </div>
                        <div x-show="show" x-cloak
                             class="absolute z-10 card-theme text-xs rounded-[var(--radius-card)] p-3 mt-1 min-w-44 left-0" style="box-shadow: var(--shadow-dark);">
                            <div class="relative z-10">
                                <p class="font-semibold mb-1.5 border-b pb-1.5 text-accent" style="border-color: var(--border-subtle);">{{ $day->translatedFormat('j F') }}</p>
                                @forelse ($dayTasks as $task)
                                    <div class="flex items-center gap-1.5 mb-1.5 last:mb-0">
                                        <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $task->subject->color }}"></span>
                                        <span class="truncate text-primary/70">{{ $task->title }}</span>
                                    </div>
                                @empty
                                    <span style="color: rgba(var(--text-secondary-rgb), 0.3);">Sin tareas</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
