<?php
namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Http\Request;
class TagController extends Controller
{
    public function index()
    {
        return Tag::where('user_id', auth()->id())->get();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:7'],
        ]);
        $tag = Tag::create([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'user_id' => auth()->id(),
        ]);
        if ($request->wantsJson()) {
            return response()->json($tag, 201);
        }
        return redirect()->back()->with('success', 'Etiqueta creada.');
    }
    public function destroy(Tag $tag)
    {
        if ($tag->user_id !== auth()->id()) abort(403);
        $tag->delete();
        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->back()->with('success', 'Etiqueta eliminada.');
    }
}