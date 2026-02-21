# TaskFlow — Professional SaaS Task Management Platform

![TaskFlow Banner](https://via.placeholder.com/1200x400/6366f1/ffffff?text=TaskFlow+SaaS+Platform)

> A production-ready, multi-tenant SaaS task management platform built with Laravel 10, MySQL, pure CSS, and Vite. Portfolio-grade code quality.

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
├── app/
│   ├── Console/                    # Artisan commands
│   ├── Exceptions/                 # Exception handler
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/               # Auth controllers (Login, Register, Reset, Profile)
│   │   │   ├── Dashboard/          # Dashboard & team switching
│   │   │   ├── Task/               # Task CRUD controller
│   │   │   └── Team/               # Team & member controllers
│   │   ├── Middleware/             # HTTP middleware
│   │   └── Requests/               # Form Request validation
│   │       ├── Auth/
│   │       ├── Task/
│   │       └── Team/
│   ├── Models/                     # Eloquent models (User, Team, Task, ActivityLog)
│   ├── Policies/                   # Laravel authorization policies
│   ├── Providers/                  # Service providers
│   ├── Services/                   # Business logic services (ActivityLogService)
│   └── View/Components/            # Blade component classes
├── database/
│   ├── migrations/                 # All database migrations (timestamped)
│   ├── seeders/                    # Demo data seeder
│   └── factories/                  # (Optional) Model factories
├── docker/
│   ├── nginx.conf                  # Nginx server configuration
│   ├── php-fpm.conf                # PHP-FPM worker configuration
│   └── entrypoint.sh               # Container startup script
├── public/                         # Web root (served by Nginx)
│   └── index.php                   # Laravel front controller
├── resources/
│   ├── css/
│   │   └── app.css                 # Complete design system (2000+ lines, no frameworks)
│   ├── js/
│   │   ├── app.js                  # Application JavaScript (theme, UI, charts)
│   │   └── bootstrap.js            # Axios setup
│   └── views/
│       ├── auth/                   # Login, register, forgot-password, etc.
│       ├── dashboard/              # Dashboard & no-team state
│       ├── layouts/                # app.blade.php, guest.blade.php
│       ├── tasks/                  # Task CRUD views
│       ├── teams/                  # Team CRUD + member management
│       ├── activity/               # Activity timeline
│       ├── profile/                # User profile settings
│       └── vendor/pagination/      # Custom pagination component
├── routes/
│   ├── web.php                     # All web routes
│   ├── api.php                     # API routes (Sanctum)
│   └── console.php                 # Artisan routes
├── Dockerfile                      # Multi-stage Docker build
├── docker-compose.yml              # Full stack with MySQL
├── vite.config.js                  # Vite configuration
├── package.json                    # Node.js dependencies
├── composer.json                   # PHP dependencies
└── .env.example                    # Environment template
```

---

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer 2+
- Node.js 18+ & npm
- MySQL 8.0+

### Steps
Requirements

PHP 8.1+
Composer
MySQL
Node.js & NPM

Installation
bashgit clone https://github.com/marouaneradi/TaskFlow-Professional-SaaS-Task-Management-Platform.git
cd taskflow
1. Install dependencies
bash 
   -composer install
   -npm install
2. Environment setup
bashcp
- .env.example .env
- php artisan key:generate
3. Configure your database
Open .env and update these lines with your MySQL credentials:
envDB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=your_password
4. Run migrations & seed demo data
bash
-php artisan migrate --seed
5. Build frontend assets
bash
-npm run build
6. Start the server
bashphp artisan serve
Visit http://localhost:8000

Demo Accounts
Email Password radimarouane05@gmail.com password 

Tech Stack

Laravel 10, PHP 8.1+
Laravel Breeze (auth)
Laravel Sanctum
MySQL
Vite 

License
MIT