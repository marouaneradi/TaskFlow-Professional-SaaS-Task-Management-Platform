# 🚀 TaskFlow -- Professional SaaS Task Management Platform

TaskFlow is a modern SaaS task management platform built with Laravel.
It allows teams to manage projects, assign tasks, and collaborate
efficiently.

---

## 📋 Table of Contents

- [Description](#description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Screenshots](#screenshots)
- [Database Schema](#database-schema)
- [Folder Structure](#folder-structure)
- [Installation](#installation)
- [Production Build](#production-build)
- [Deployment](#deployment)
- [License](#license)

---

## 📖 Description

TaskFlow is a full-featured, multi-tenant SaaS task management application that allows teams to collaborate on tasks, track progress, and stay productive. Built as a portfolio-level project demonstrating modern Laravel architecture patterns including domain-driven controllers, Laravel Policies, Form Requests, Services, and Blade component-based views.

---

## ✨ Features

### Authentication
- ✅ Email & password registration with validation
- ✅ Secure login with rate limiting (5 attempts/minute)
- ✅ Email verification (MustVerifyEmail)
- ✅ Password reset via email
- ✅ Remember me (30-day sessions)
- ✅ Secure logout

### Multi-Tenant SaaS
- ✅ Users can create and belong to multiple teams
- ✅ Role-based access inside each team (Owner, Admin, Member)
- ✅ Team switching in the UI
- ✅ Team isolation — users only see their team's data

### Roles & Permissions (Laravel Policies)
| Role   | Create Task | Edit Any Task | Delete Task | Manage Members | Delete Team |
|--------|:-----------:|:-------------:|:-----------:|:--------------:|:-----------:|
| Owner  | ✅          | ✅            | ✅          | ✅             | ✅          |
| Admin  | ✅          | ✅            | ✅          | ✅             | ❌          |
| Member | ✅          | Own only      | Own only    | ❌             | ❌          |

### Task Management
- ✅ Full CRUD (Create, Read, Update, Delete)
- ✅ Assign tasks to team members
- ✅ Status: Todo / In Progress / Done
- ✅ Priority: Low / Medium / High
- ✅ Due date with overdue detection
- ✅ Soft deletes
- ✅ Advanced filtering (status, priority, assignee, date range)
- ✅ Full-text search
- ✅ Sortable columns
- ✅ Paginated results (15/page)
- ✅ Quick status updates

### Dashboard
- ✅ Team statistics overview (total, in-progress, done, overdue)
- ✅ Animated bar chart (tasks by status)
- ✅ Completion rate ring indicator
- ✅ Tasks due soon (next 3 days)
- ✅ Recent activity feed
- ✅ Productivity metrics (completed this week)

### Activity Log System
- ✅ Task created
- ✅ Task updated (field-level changes)
- ✅ Status changed
- ✅ Task deleted
- ✅ Member added to team
- ✅ Member removed from team
- ✅ Task assigned
- ✅ Task completed
- ✅ Timeline view per team and per task
- ✅ Paginated activity feed

### UI/UX
- ✅ Modern SaaS layout (Fixed Sidebar + Sticky Topbar)
- ✅ Professional design system with CSS variables
- ✅ Dark mode toggle (persisted in database + localStorage)
- ✅ Smooth animations and transitions
- ✅ Flash notification system (auto-dismiss at 4s)
- ✅ Responsive design (mobile-first)
- ✅ Custom pure CSS — zero Tailwind, zero Bootstrap
- ✅ Inter font (Google Fonts)
- ✅ Custom pagination component
- ✅ Dropdown menus
- ✅ Modals with keyboard (Escape) support
- ✅ Loading state animations

---

## 🛠 Tech Stack

| Layer           | Technology                           |
|-----------------|--------------------------------------|
| Framework       | Laravel 10                           |
| PHP             | PHP 8.2                              |
| Database        | MySQL 8.0                            |
| Frontend Build  | Vite 4                               |
| Templates       | Blade + Blade Components             |
| Styling         | Pure CSS (Custom Design System)      |
| Auth            | Laravel Breeze pattern (custom)      |
| Fonts           | Inter (Google Fonts)                 |
| HTTP Client     | Axios                                |
| Containerization| Docker + Nginx + PHP-FPM             |

---



## 🗄 Database Schema

### Tables Overview

```
users
├── id (bigint, PK)
├── name (varchar 255)
├── email (varchar 255, unique, indexed)
├── email_verified_at (timestamp, nullable)
├── password (varchar 255)
├── avatar (varchar, nullable)
├── timezone (varchar, default 'UTC')
├── theme (enum: light/dark, default 'light')
├── remember_token
└── timestamps

teams
├── id (bigint, PK)
├── name (varchar 100)
├── slug (varchar, unique, indexed)
├── description (text, nullable)
├── avatar (varchar, nullable)
├── owner_id (FK → users.id, CASCADE)
├── timestamps
└── deleted_at (soft delete)

team_user [PIVOT]
├── id (bigint, PK)
├── team_id (FK → teams.id, CASCADE, indexed)
├── user_id (FK → users.id, CASCADE, indexed)
├── role (enum: owner/admin/member)
├── joined_at (timestamp, nullable)
└── timestamps
UNIQUE: (team_id, user_id)

tasks
├── id (bigint, PK)
├── title (varchar 255, fulltext indexed)
├── description (text, nullable, fulltext indexed)
├── team_id (FK → teams.id, CASCADE, indexed)
├── created_by (FK → users.id, CASCADE, indexed)
├── assigned_to (FK → users.id, SET NULL, indexed, nullable)
├── status (enum: todo/in_progress/done, indexed)
├── priority (enum: low/medium/high, indexed)
├── due_date (date, nullable, indexed)
├── completed_at (timestamp, nullable)
├── timestamps
└── deleted_at (soft delete)

activity_logs
├── id (bigint, PK)
├── team_id (FK → teams.id, CASCADE, indexed)
├── user_id (FK → users.id, CASCADE, indexed)
├── task_id (FK → tasks.id, SET NULL, nullable, indexed)
├── action (varchar, indexed)
├── subject_type (varchar, nullable)
├── subject_id (bigint, nullable)
├── properties (json, nullable)
├── description (text)
└── timestamps
```

### Key Relationships
- User `belongsToMany` Teams (via team_user pivot)
- Team `hasMany` Tasks
- Team `hasMany` ActivityLogs
- Task `belongsTo` Team, Creator (User), Assignee (User)
- ActivityLog `belongsTo` Team, User, Task

### Performance Optimizations
- **Indexes**: Composite indexes on `(team_id, status)`, `(team_id, priority)`, `(team_id, due_date)`, `(team_id, created_at)`
- **Full-text indexes**: `tasks.title` + `tasks.description` for LIKE search
- **Eager loading**: All relationships are eager-loaded to prevent N+1 queries
- **Pagination**: All list views paginate with 15 records/page

---

## 📁 Folder Structure

```
taskflow/
│
├── .env.example                         Environment variables template (DB, mail, app key, etc.)
├── .gitignore                           Files/folders excluded from Git (vendor, .env, node_modules...)
├── artisan                              Laravel CLI entry point (run commands like migrate, serve, etc.)
├── composer.json                        PHP dependencies and autoload configuration
├── Dockerfile                           Docker image definition for containerized deployment
├── docker-compose.yml                   Multi-container Docker setup (app, nginx, mysql)
├── package.json                         Node.js dependencies (Vite, TailwindCSS)
├── phpunit.xml                          PHPUnit test configuration
├── README.md                            Project documentation and setup guide
├── vite.config.js                       Vite bundler config for JS/CSS assets
│
├── app/
│   ├── Console/
│   │   └── Kernel.php                   Registers scheduled commands
│   ├── Exceptions/
│   │   └── Handler.php                  Global exception and error handler
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php           Base controller all others extend
│   │   │   ├── ActivityController.php   Shows team activity log
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php   Login / logout
│   │   │   │   ├── EmailVerificationController.php      Email verification flow
│   │   │   │   ├── PasswordResetController.php          Forgot & reset password
│   │   │   │   ├── ProfileController.php                Edit profile & change password
│   │   │   │   └── RegisteredUserController.php         User registration
│   │   │   ├── Dashboard/
│   │   │   │   └── DashboardController.php              Main dashboard with team stats & tasks
│   │   │   ├── Task/
│   │   │   │   └── TaskController.php                   CRUD for tasks + status updates
│   │   │   └── Team/
│   │   │       ├── TeamController.php                   CRUD for teams
│   │   │       └── TeamMemberController.php             Add/remove/role members in a team
│   │   ├── Kernel.php                   Registers global and route middleware
│   │   ├── Middleware/
│   │   │   ├── EncryptCookies.php                       Encrypts cookies
│   │   │   ├── PreventRequestsDuringMaintenance.php     Blocks requests in maintenance mode
│   │   │   ├── RedirectIfAuthenticated.php              Redirects logged-in users away from guest pages
│   │   │   ├── TrimStrings.php                          Trims whitespace from input
│   │   │   ├── TrustProxies.php                         Trusted proxy configuration
│   │   │   ├── ValidateSignature.php                    Validates signed URLs
│   │   │   └── VerifyCsrfToken.php                      CSRF protection
│   │   └── Requests/
│   │       ├── Auth/
│   │       │   └── LoginRequest.php                     Validates login credentials
│   │       ├── Task/
│   │       │   ├── StoreTaskRequest.php                 Validates new task creation
│   │       │   └── UpdateTaskRequest.php                Validates task edits
│   │       └── Team/
│   │           ├── StoreTeamRequest.php                 Validates new team creation
│   │           └── UpdateTeamRequest.php                Validates team edits
│   ├── Models/
│   │   ├── ActivityLog.php              Activity log model (who did what in a team)
│   │   ├── Task.php                     Task model with scopes, relationships, and stats
│   │   ├── Team.php                     Team model with members, tasks, and stats
│   │   └── User.php                     User model with teams and auth
│   ├── Policies/
│   │   ├── TaskPolicy.php               Authorization rules for task actions
│   │   └── TeamPolicy.php               Authorization rules for team actions
│   ├── Providers/
│   │   ├── AppServiceProvider.php       App boot logic (sets default string length for MySQL)
│   │   ├── AuthServiceProvider.php      Registers policies and gates
│   │   └── RouteServiceProvider.php     Loads and configures routes
│   ├── Services/
│   │   └── ActivityLogService.php       Handles writing activity log entries
│   └── View/Components/
│       ├── AppLayout.php                Authenticated layout component
│       └── GuestLayout.php             Guest (login/register) layout component
│
├── bootstrap/
│   ├── app.php                          Creates and configures the Laravel application
│   └── cache/                           Cached config and routes (auto-generated)
│
├── config/
│   ├── app.php                          App name, timezone, locale, providers
│   ├── auth.php                         Auth guards and user providers
│   ├── broadcasting.php                 WebSocket/event broadcasting config
│   ├── cache.php                        Cache driver config (file, redis, etc.)
│   ├── cors.php                         Cross-Origin Resource Sharing settings
│   ├── database.php                     Database connections config
│   ├── filesystems.php                  File storage disks config
│   ├── hashing.php                      Password hashing driver (bcrypt)
│   ├── logging.php                      Log channels config
│   ├── mail.php                         Mail driver and SMTP config
│   ├── queue.php                        Queue driver config
│   ├── sanctum.php                      API token auth config
│   ├── services.php                     Third-party services (Mailgun, SES, etc.)
│   ├── session.php                      Session driver and lifetime config
│   └── view.php                         Blade view paths and compiled views location
│
├── database/
│   ├── factories/                       Model factories for test data generation
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php              Users table
│   │   ├── 2014_10_12_100000_create_password_reset_tokens_table.php  Password resets
│   │   ├── 2014_10_12_200000_create_sessions_table.php           Sessions table
│   │   ├── 2024_01_01_000001_create_teams_table.php              Teams table
│   │   ├── 2024_01_01_000002_create_team_user_table.php          Team members pivot table
│   │   ├── 2024_01_01_000003_create_tasks_table.php              Tasks table
│   │   └── 2024_01_01_000004_create_activity_logs_table.php      Activity logs table
│   └── seeders/
│       └── DatabaseSeeder.php           Seeds demo users, teams, tasks and activity logs
│
├── docker/
│   ├── entrypoint.sh                    Docker container startup script
│   ├── nginx.conf                       Nginx web server configuration
│   └── php-fpm.conf                     PHP-FPM process manager configuration
│
├── public/
│   ├── .htaccess                        Apache URL rewriting rules
│   ├── index.php                        Application entry point (all requests go here)
│   └── robots.txt                       Search engine crawler instructions
│
├── resources/
│   ├── css/
│   │   └── app.css                      Main stylesheet (TailwindCSS)
│   ├── js/
│   │   ├── app.js                       Main JavaScript entry point
│   │   └── bootstrap.js                 Axios and JS library setup
│   └── views/
│       ├── activity/
│       │   └── index.blade.php          Team activity log page
│       ├── auth/
│       │   ├── forgot-password.blade.php   Forgot password form
│       │   ├── login.blade.php             Login form
│       │   ├── register.blade.php          Registration form
│       │   ├── reset-password.blade.php    Reset password form
│       │   └── verify-email.blade.php      Email verification notice
│       ├── dashboard/
│       │   ├── index.blade.php          Main dashboard view (stats, tasks, team)
│       │   └── no-team.blade.php        Shown when user has no team yet
│       ├── layouts/
│       │   ├── app.blade.php            Authenticated app shell (nav, sidebar)
│       │   └── guest.blade.php          Guest pages shell (login/register wrapper)
│       ├── profile/
│       │   └── edit.blade.php           Profile edit page (name, email, password)
│       ├── tasks/
│       │   ├── create.blade.php         Create task form
│       │   ├── edit.blade.php           Edit task form
│       │   ├── index.blade.php          Task list with filters
│       │   └── show.blade.php           Single task detail view
│       └── teams/
│           ├── create.blade.php         Create team form
│           ├── edit.blade.php           Edit team form
│           ├── index.blade.php          Teams list
│           └── show.blade.php           Team detail with members and tasks
│
├── routes/
│   ├── api.php                          API routes (stateless)
│   ├── console.php                      Artisan console command routes
│   └── web.php                          All web routes (auth, dashboard, teams, tasks)
│
├── storage/
│   ├── app/public/                      User-uploaded files
│   ├── framework/
│   │   ├── cache/                       Framework cache files
│   │   ├── sessions/                    Session files
│   │   ├── testing/                     Test storage
│   │   └── views/                       Compiled Blade templates
│   └── logs/                            Application log files
│
└── tests/
    ├── CreatesApplication.php           Trait to boot the app in tests
    ├── TestCase.php                     Base test class
    ├── Feature/                         Feature/integration tests
    └── Unit/                            Unit tests
```

---

## 🚀 Installation

📦 Tech Stack

Laravel 10

PHP 8.1+

Laravel Breeze (Authentication)

Laravel Sanctum

MySQL

Vite

Node.js & NPM

⚙️ Prerequisites

Make sure you have installed:

PHP 8.1+

Composer 2+

MySQL 8.0+

Node.js 18+

NPM

## 📦 Tech Stack

-   Laravel 10
-   PHP 8.1+
-   Laravel Breeze (Authentication)
-   Laravel Sanctum
-   MySQL
-   Vite
-   Node.js & NPM

------------------------------------------------------------------------

## ⚙️ Prerequisites

Make sure you have installed:

-   PHP 8.1+
-   Composer 2+
-   MySQL 8.0+
-   Node.js 18+
-   NPM

------------------------------------------------------------------------

## 🚀 Installation

### 1️⃣ Clone the repository

``` bash
git clone https://github.com/marouaneradi/TaskFlow-Professional-SaaS-Task-Management-Platform.git
cd TaskFlow-Professional-SaaS-Task-Management-Platform
```

### 2️⃣ Install dependencies

``` bash
composer install
npm install
```

### 3️⃣ Environment setup

``` bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configure Database

Open the `.env` file and update your MySQL credentials:

``` env
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5️⃣ Run migrations & seed demo data

``` bash
php artisan migrate --seed
```

### 6️⃣ Build frontend assets

``` bash
npm run build
```

### 7️⃣ Start the development server

``` bash
php artisan serve
```

Visit:

http://localhost:8000

------------------------------------------------------------------------

## 🔐 Demo Account

Email: radimarouane05@gmail.com\
Password: password

------------------------------------------------------------------------


