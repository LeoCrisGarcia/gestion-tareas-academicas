<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use Illuminate\Http\Request;
class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('user_id', auth()->id())->get();
        return view('subjects.index', compact('subjects'));
    }
    public function create()
    {
        return view('subjects.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:7'],
        ]);
        Subject::create([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('subjects.index')->with('success', 'Materia creada.');
    }
    public function edit(Subject $subject)
    {
        $this->authorizeAccess($subject);
        return view('subjects.edit', compact('subject'));
    }
    public function update(Request $request, Subject $subject)
    {
        $this->authorizeAccess($subject);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:7'],
        ]);
        $subject->update($validated);
        return redirect()->route('subjects.index')->with('success', 'Materia actualizada.');
    }
    public function destroy(Subject $subject)
    {
        $this->authorizeAccess($subject);
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Materia eliminada.');
    }
    private function authorizeAccess(Subject $subject): void
    {
        if ($subject->user_id !== auth()->id()) {
            abort(403);
        }
    }
}