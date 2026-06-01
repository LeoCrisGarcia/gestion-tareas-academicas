<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', auth()->id())->with('subject');
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        $tasks = $query->with('tags')->orderBy('due_date')->get();
        $subjects = Subject::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        return view('tasks.index', compact('tasks', 'subjects', 'tags'));
    }
    public function create()
    {
        $subjects = Subject::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        return view('tasks.create', compact('subjects', 'tags'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:baja,media,alta'],
        ]);
        $task = Task::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pendiente',
        ]);
        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }
        return redirect()->route('tasks.index')->with('success', 'Tarea creada.');
    }
    public function edit(Task $task)
    {
        $this->authorizeAccess($task);
        if ($task->status === 'completada') {
            return redirect()->route('tasks.index')->with('error', 'No se puede editar una tarea completada.');
        }
        $subjects = Subject::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        return view('tasks.edit', compact('task', 'subjects', 'tags'));
    }
    public function update(Request $request, Task $task)
    {
        $this->authorizeAccess($task);
        if ($task->status === 'completada') {
            return redirect()->route('tasks.index')->with('error', 'No se puede editar una tarea completada.');
        }
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:baja,media,alta'],
            'status' => ['required', 'in:pendiente,en_progreso,completada'],
        ]);
        $task->update($validated);
        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        } else {
            $task->tags()->detach();
        }
        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada.');
    }
    public function destroy(Task $task)
    {
        $this->authorizeAccess($task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada.');
    }
    public function toggle(Task $task)
    {
        $this->authorizeAccess($task);
        $task->update([
            'status' => $task->status === 'completada' ? 'pendiente' : 'completada',
        ]);
        return back()->with('success', 'Estado actualizado.');
    }
    private function authorizeAccess(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
