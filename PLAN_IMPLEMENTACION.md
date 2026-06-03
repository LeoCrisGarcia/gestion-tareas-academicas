# Plan de Implementación — Gestión de Tareas Académicas

## Stack

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 13 + PHP 8.3 |
| Frontend | Blade + Tailwind CSS v4 + Alpine.js |
| BD | MySQL |
| Auth | Custom (login con email + contraseña, sesión) |

---

## Base de datos

```sql
users
  id, name, email, password, remember_token, timestamps

subjects
  id, name, color, user_id (FK), created_at

tasks
  id, subject_id (FK), title, description,
  due_date, priority (baja/media/alta),
  status (pendiente/en_progreso/completada),
  user_id (FK), created_at, updated_at
```

- `subjects.user_id` → usuario propietario de la materia
- `tasks.user_id` → usuario propietario de la tarea

---

## Plan por fases

### Fase 1 — Auth custom + base

- [ ] Login/Register manual con validación y sesión
- [ ] Logout + middleware de autenticación
- [ ] Middleware de autenticación (solo usuarios logueados)
- [ ] Layout base con Tailwind + Alpine.js + navegación
- [ ] Modelos: `Subject`, `Task` con relaciones y `fillable`
- [ ] Seeders con usuarios, materias y tareas de prueba
- [ ] Ruta `/dashboard` protegida

### Fase 2 — CRUD

- [ ] CRUD de materias (nombre + color, por usuario)
- [ ] CRUD de tareas (materia, título, descripción, fecha, prioridad, estado)
- [ ] Cada usuario ve y administra solo sus propias materias y tareas
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
