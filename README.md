# Laravel Vue Blog

Blog application built with **Laravel 10** + **Vue.js** + **Vite**.

## Tech Stack

- **Backend:** Laravel 10, PHP 8.1+, Sanctum, MySQL
- **Frontend:** Vue 3, Vue Router, Axios
- **Build:** Vite + laravel-vite-plugin

## Requisitos

- PHP ^8.1
- Composer
- Node.js
- MySQL

## Instalación

```bash
# Clonar repositorio
git clone https://github.com/AnthonyAndino/laravel-react.git
cd laravel-react

# Instalar dependencias PHP
composer install

# Instalar dependencias JS
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## Desarrollo

```bash
npm run dev
php artisan serve
```

## Licencia

MIT
