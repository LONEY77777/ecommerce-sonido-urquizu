# Sistema Web de Comercio Electrónico – Equipos de Sonido

**Estudiante:** Carlos Daniel Urquizu Mamani
**Asignatura:** Proyecto Integrador Intermedio II – Universidad Privada Franz Tamayo (UNIFRANZ)
**Docente:** Lic. Patricia Fernández Quisbert

## Descripción
Tienda virtual dedicada a la venta de equipos de sonido: consolas, parlantes, instrumentos musicales, cables y accesorios para eventos. Los clientes pueden ver el catálogo, buscar productos, registrarse, usar un carrito y realizar pedidos. El administrador gestiona productos, pedidos, inventario y usuarios desde un panel propio.

## Tecnologías
PHP 8.2, Laravel 12, Blade, Tailwind CSS 4, Vite, MySQL 8.

## Funcionalidades
- Registro, inicio de sesión y roles (administrador y cliente)
- Catálogo con búsqueda y filtro por categoría
- Carrito de compras y pedidos
- Control de inventario
- Panel administrativo

## Instalación
1. Clonar el repositorio: `git clone <URL-del-repositorio>`
2. `composer install` y `npm install`
3. Copiar `.env.example` a `.env`, ejecutar `php artisan key:generate` y configurar la base de datos MySQL
4. `php artisan migrate --seed`
5. `npm run build` y `php artisan serve`

## Estado
Proyecto en desarrollo (Hito 3). Pendientes: pasarela de pagos, reportes y despliegue.
