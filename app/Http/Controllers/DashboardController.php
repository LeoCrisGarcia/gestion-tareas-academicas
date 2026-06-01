<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Task;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->get('status'); // pendiente, completada, null = todas
        $tasksQuery = Task::where('user_id', $userId)->with('subject');
        if ($filter) {
            $tasksQuery->where('status', $filter);
        }
        $tasks = $tasksQuery->orderBy('due_date')->get();
        $today = now()->startOfDay();
        $next7 = now()->addDays(7)->endOfDay();
        $stats = [
            'vencen_hoy' => $tasks->filter(fn($t) => $t->due_date?->isSameDay($today)),
            'vencidas' => $tasks->filter(fn($t) => $t->due_date && $t->due_date->isPast() && $t->status !== 'completada'),
            'proximos_7' => $tasks->filter(fn($t) => $t->due_date && $t->due_date->between($today->addDay(), $next7)),
            'sin_fecha' => $tasks->filter(fn($t) => !$t->due_date),
        ];
        $subjects = Subject::where('user_id', $userId)->withCount(['tasks' => function($q) {
            $q->where('user_id', auth()->id());
        }])->get();
        $totalTasks = $tasks->count();
        $maxTasks = $subjects->max('tasks_count') ?: 1;
        return view('dashboard', compact('tasks', 'stats', 'subjects', 'filter', 'totalTasks', 'maxTasks'));
    }
}