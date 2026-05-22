# Laravel Vue Blog

Blog application built with **Laravel 10** + **Vue.js** + **Vite**.

## Tech Stack

- **Backend:** Laravel 10, PHP 8.1+, Sanctum (API auth), MySQL
- **Frontend:** Vue 3, Vue Router, Axios
- **Build:** Vite + laravel-vite-plugin

## Features

- API authentication (register, login, logout) with Sanctum
- Posts CRUD with pagination, image upload, and publish/draft
- Categories CRUD with post count
- User profile management (update profile, change password, Gravatar)
- Dashboard with stats (posts, categories, users)
- Category filtering on posts

## API Endpoints

### Auth
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | No | Register new user |
| POST | `/api/login` | No | Login |
| POST | `/api/logout` | Yes | Logout |
| GET | `/api/user` | Yes | Get current user |

### Posts
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/posts` | No | List posts (paginated, filterable by category) |
| GET | `/api/posts/{post}` | No | Show post |
| POST | `/api/posts` | Yes | Create post |
| PUT | `/api/posts/{post}` | Yes | Update post |
| DELETE | `/api/posts/{post}` | Yes | Delete post |
| POST | `/api/posts/{post}/publish` | Yes | Publish post |
| POST | `/api/posts/{post}/image` | Yes | Upload post image |

### Categories
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/categories` | No | List categories (with post count) |
| GET | `/api/categories/{category}` | Yes | Show category with posts |
| POST | `/api/categories` | Yes | Create category |
| PUT | `/api/categories/{category}` | Yes | Update category |
| DELETE | `/api/categories/{category}` | Yes | Delete category |

### Profile
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/profile` | Yes | Get profile with Gravatar |
| PUT | `/api/profile` | Yes | Update profile |
| PUT | `/api/profile/password` | Yes | Change password |

### Dashboard
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/dashboard` | Yes | Stats + recent posts + popular categories |

## Requisitos

- PHP ^8.1
- Composer
- Node.js
- MySQL (or SQLite for testing)

## Instalación

```bash
git clone https://github.com/AnthonyAndino/laravel-react.git
cd laravel-react

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate

npm run build
php artisan serve
```

## Desarrollo

```bash
npm run dev
php artisan serve
```

## Tests

```bash
php artisan test
```

## Licencia

MIT
