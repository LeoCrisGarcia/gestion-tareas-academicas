<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{
    // Muestra el formulario de registro
    public function create()
    {
        return view('auth.register');
    }
    // Procesa el registro
    public function store(Request $request)
    {
        // 1. Validar los datos
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        // 2. Crear el usuario en BD
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        // 3. Iniciar sesión automáticamente
        Auth::login($user);
        // 4. Redirigir al dashboard
        return redirect()->intended('/dashboard');
    }
}