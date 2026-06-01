<?php
namespace Database\Seeders;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Leonardo',
            'email' => 'leo@test.com',
            'password' => bcrypt('password'),
        ]);
        $matematicas = Subject::create(['name' => 'Matemáticas', 'color' => '#3B82F6', 'user_id' => $user->id]);
        $lengua = Subject::create(['name' => 'Lengua', 'color' => '#10B981', 'user_id' => $user->id]);
        $historia = Subject::create(['name' => 'Historia', 'color' => '#F59E0B', 'user_id' => $user->id]);
        Task::create(['subject_id' => $matematicas->id, 'title' => 'Ejercicios de álgebra', 'due_date' => now()->addDays(3), 'priority' => 'alta', 'status' => 'pendiente', 'user_id' => $user->id]);
        Task::create(['subject_id' => $lengua->id, 'title' => 'Leer capítulo 5', 'due_date' => now()->addDay(), 'priority' => 'media', 'status' => 'pendiente', 'user_id' => $user->id]);
        Task::create(['subject_id' => $matematicas->id, 'title' => 'TP final', 'due_date' => now()->addDays(10), 'priority' => 'baja', 'status' => 'pendiente', 'user_id' => $user->id]);
        Task::create(['subject_id' => $historia->id, 'title' => 'Reseña del libro', 'due_date' => now()->subDay(), 'priority' => 'alta', 'status' => 'vencida', 'user_id' => $user->id]);
        Task::create(['subject_id' => $lengua->id, 'title' => 'Análisis sintáctico', 'due_date' => now()->addDays(5), 'priority' => 'media', 'status' => 'pendiente', 'user_id' => $user->id]);
        Task::create(['subject_id' => $matematicas->id, 'title' => 'Integrales resueltas', 'due_date' => now()->subDays(2), 'priority' => 'media', 'status' => 'completada', 'user_id' => $user->id]);
        Task::create(['subject_id' => $historia->id, 'title' => 'Línea de tiempo', 'due_date' => now()->addDays(7), 'priority' => 'baja', 'status' => 'pendiente', 'user_id' => $user->id]);
    }
}