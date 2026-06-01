<?php
namespace App\Http\Controllers;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $date = Carbon::createFromDate($year, $month, 1);
        $startOfWeek = $date->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        $tasks = Task::where('user_id', auth()->id())
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
            ->with('subject')
            ->get()
            ->groupBy(fn($t) => $t->due_date->format('Y-m-d'));
        return view('calendar.index', compact('tasks', 'date', 'month', 'year', 'startOfWeek', 'endOfWeek'));
    }
}