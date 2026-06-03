# TaskFlow — Gestión de Tareas Académicas

Aplicación web para gestionar tareas, materias y calendario académico. Desarrollada con **Laravel 13**, **MySQL**, **Tailwind CSS v4** y **Alpine.js**.

## Requisitos

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 20+ y pnpm (o npm)

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/LeoCrisGarcia/gestion-tareas-academicas.git
cd gestion-tareas-academicas

# 2. Instalar dependencias de PHP
composer install

# 3. Copiar y configurar variables de entorno
cp .env.example .env
php artisan key:generate
```

Editar `.env` y ajustar los datos de conexión a tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_tareas
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

## Crear la base de datos

Conéctate a MySQL y ejecutá:

```sql
CREATE DATABASE gestion_tareas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O desde la terminal:

```bash
mysql -u root -p -e "CREATE DATABASE gestion_tareas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Migraciones y datos de prueba

```bash
# Ejecutar migraciones (crea las tablas)
php artisan migrate

# Cargar datos de prueba (usuario, materias y tareas)
php artisan db:seed
```

Esto crea un usuario de prueba:

| Campo    | Valor          |
| -------- | -------------- |
| Email    | leo@test.com   |
| Password | password       |

Junto con 3 materias y 7 tareas de ejemplo.

## Frontend

```bash
# Instalar dependencias de JS
pnpm install

# Compilar assets y arrancar el servidor de desarrollo
pnpm run dev
```

## Servidor de desarrollo

```bash
php artisan serve
```

La app estará disponible en `http://localhost:8000`.

## Resetear todo (si necesitás empezar de cero)

```bash
php artisan migrate:fresh --seed
```

Esto borra todas las tablas, las recrea y vuelve a cargar los datos de prueba.
