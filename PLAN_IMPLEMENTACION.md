# Plan de Implementación — Gestión de Tareas Académicas

## Stack

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 13 + PHP 8.3 |
| Frontend | Blade + Tailwind CSS v4 + Alpine.js |
| BD | SQLite |
| Auth | Custom (login con email + contraseña, sesión, roles) |

---

## Base de datos

```sql
users
  id, name, email, password, role (estudiante/profesor), remember_token, timestamps

subjects
  id, name, color, user_id (FK), created_at

tasks
  id, subject_id (FK), title, description,
  due_date, priority (baja/media/alta),
  status (pendiente/en_progreso/completada),
  user_id (FK), created_at, updated_at
```

- `users.role` → `estudiante` | `profesor`
- `subjects.user_id` → profesor que creó la materia
- `tasks.user_id` → estudiante asignado (opcional, nullable)

---

## Plan por fases

### Fase 1 — Auth custom + base

- [ ] Login/Register manual con validación y sesión
- [ ] Logout + middleware de autenticación
- [ ] Middleware de roles (`CheckRole: profesor`)
- [ ] Layout base con Tailwind + Alpine.js + navegación
- [ ] Modelos: `Subject`, `Task` con relaciones y `fillable`
- [ ] Seeders con usuarios, materias y tareas de prueba
- [ ] Ruta `/dashboard` protegida

### Fase 2 — CRUD

- [ ] CRUD de materias (nombre + color, solo profesor)
- [ ] CRUD de tareas (materia, título, descripción, fecha, prioridad, estado)
- [ ] Estudiante: solo ve sus tareas; profesor: ve y administra
- [ ] Toggle completada vía Alpine.js (fetch + actualización en caliente)

### Fase 3 — Dashboard

- [ ] Vista principal con cards:
  - Vencen hoy
  - Vencidas
  - Próximos 7 días
  - Sin fecha
- [ ] Tareas por materia (gráfico de barras simple con CSS)
- [ ] Filtro rápido: pendientes, completadas, todas

### Fase 4 — Filtros y calendario

- [ ] Filtros combinados: materia, estado, prioridad, rango de fechas
- [ ] Vista calendario (grid CSS, resaltar días con tareas)
- [ ] Búsqueda por texto en título/descripción

### Fase 5 — Polish

- [ ] Notificaciones vencimiento (opcional, JS nativo)
- [ ] Confirmación antes de eliminar (Alpine.js)
- [ ] Mensajes flash y validación visual (Tailwind)
- [ ] Tests con PHPUnit (Feature tests de auth y CRUDs)

---

## Entrega

Cada fase termina con `php artisan migrate:fresh --seed` funcional y se puede verificar en el navegador.
