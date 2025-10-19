# 🎯 Mini Issue Tracker

A comprehensive RESTful API for managing projects, issues, labels, and comments. Built with Laravel 11, this application provides a complete issue tracking system similar to GitHub Issues or Jira, featuring JWT authentication, advanced relationships, custom casts, and extensive API endpoints.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Running the Application](#-running-the-application)
- [API Documentation](#-api-documentation)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Authentication](#-authentication)
- [Testing](#-testing)
- [Seeded Data](#-seeded-data)
- [Advanced Features](#-advanced-features)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### Core Functionality
- **User Management** - Complete user registration, authentication, and profile management
- **Project Management** - Create and manage multiple projects with unique codes
- **Issue Tracking** - Full CRUD operations for issues with status and priority management
- **Label System** - Categorize issues with customizable labels and colors
- **Comments** - Threaded comments on issues for team collaboration
- **Team Collaboration** - Assign users to projects with specific roles (developer, manager, tester)

### Advanced Features
- **JWT Authentication** - Secure token-based authentication using tymon/jwt-auth
- **Custom Enums** - Type-safe status and priority enums
- **Custom Casts** - Advanced data casting for status, priority, and due windows
- **Eloquent Relationships** - Complex many-to-many and polymorphic relationships
- **Query Scopes** - Reusable query scopes for filtering and sorting
- **Pivot Tables** - Extended pivot tables with additional attributes
- **API Resources** - Structured JSON responses
- **Request Validation** - Form request validation for all inputs
- **Service Layer** - Business logic separated into service classes
- **Database Seeders** - Realistic fake data for testing

---

## 🛠 Tech Stack

- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL / SQLite
- **Authentication**: JWT (tymon/jwt-auth)
- **API**: RESTful API with JSON responses
- **Architecture**: MVC with Service Layer pattern

---

## 💻 System Requirements

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 8.0 or **PostgreSQL** >= 13 or **SQLite** >= 3.8
- **Node.js** >= 18.x (optional, for frontend assets)
- **Git**

### PHP Extensions Required
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath

---

## 🚀 Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/MiniIssueTracker.git
cd MiniIssueTracker
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```env
APP_NAME="Mini Issue Tracker"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_issue_tracker
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Generate JWT Secret

```bash
php artisan jwt:secret
```

This will update your `.env` file with the JWT secret key.

---

## 🗄 Database Setup

### Step 1: Create Database

Create a new database in MySQL:

```sql
CREATE DATABASE mini_issue_tracker;
```

Or for PostgreSQL:

```sql
CREATE DATABASE mini_issue_tracker;
```

For SQLite, update your `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Then create the file:

```bash
touch database/database.sqlite
```

### Step 2: Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables:
- `users`
- `projects`
- `issues`
- `labels`
- `comments`
- `project_user` (pivot table)
- `issue_label` (pivot table)

### Step 3: Seed the Database

```bash
php artisan db:seed
```

Or run migrations and seeders together:

```bash
php artisan migrate:fresh --seed
```

This will populate your database with:
- 11 users (1 admin + 10 team members)
- 5 projects
- 15 labels
- 25-40 issues
- 50-240 comments
- Project-user relationships
- Issue-label relationships

---

## 🏃 Running the Application

### Start the Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### Alternative: Use a Specific Port

```bash
php artisan serve --port=8080
```

### Run with Public Access (for local network testing)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

All protected routes require a JWT token in the Authorization header:

```
Authorization: Bearer {your_jwt_token}
```

### Quick Start

1. **Register a new user**:
```bash
POST /api/auth/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

2. **Login**:
```bash
POST /api/auth/login
{
    "email": "admin@issuetracker.com",
    "password": "password123"
}
```

3. **Use the returned token** for subsequent requests.

### Available Endpoints

#### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user (requires auth)

#### Projects
- `GET /api/projects` - List all projects
- `POST /api/projects` - Create new project
- `GET /api/projects/{id}` - Get single project
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project

#### Project Users
- `POST /api/projects/{project}/users` - Add user to project
- `DELETE /api/projects/{project}/users` - Remove user from project

#### Issues
- `GET /api/issues` - List all issues
- `POST /api/issues` - Create new issue
- `GET /api/issues/{id}` - Get single issue
- `PUT /api/issues/{id}` - Update issue
- `DELETE /api/issues/{id}` - Delete issue
- `GET /api/issues/opened` - Get opened issues
- `GET /api/issues/urgent` - Get urgent issues
- `GET /api/users/{user}/completed-issues-count` - Get completed issues count
- `GET /api/projects/{project}/opened-issues` - Get project's open issues

#### Labels
- `GET /api/labels` - List all labels
- `POST /api/labels` - Create new label
- `GET /api/labels/{id}` - Get single label
- `PUT /api/labels/{id}` - Update label
- `DELETE /api/labels/{id}` - Delete label

#### Issue Labels
- `POST /api/projects/{project}/issues/{issue}/labels` - Attach labels to issue
- `PUT /api/projects/{project}/issues/{issue}/labels` - Sync labels with issue
- `DELETE /api/projects/{project}/issues/{issue}/labels/{label}` - Detach label

#### Comments
- `GET /api/projects/{project}/issues/{issue}/comments` - List comments
- `POST /api/projects/{project}/issues/{issue}/comments` - Create comment
- `GET /api/comments/{id}` - Get single comment
- `PUT /api/comments/{id}` - Update comment
- `DELETE /api/comments/{id}` - Delete comment
- `GET /api/projects/{project}/issues/{issue}/comments-count` - Get comments count

#### Users
- `GET /api/users` - List all users
- `GET /api/users/{id}` - Get single user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### Detailed API Documentation

For complete API documentation with request/response examples, see:
📖 **[API_TESTING_SCENARIOS.md](API_TESTING_SCENARIOS.md)**

---

## 📁 Project Structure

```
MiniIssueTracker/
├── app/
│   ├── Casts/                    # Custom attribute casts
│   │   ├── DueWindowCast.php
│   │   ├── PriorityCast.php
│   │   └── StatusCast.php
│   ├── Enums/                    # Enum classes
│   │   ├── PriorityType.php
│   │   └── StatusType.php
│   ├── Http/
│   │   ├── Controllers/          # API controllers
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── CommentController.php
│   │   │   ├── IssueController.php
│   │   │   ├── IssueLabelController.php
│   │   │   ├── LabelController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── ProjectUserController.php
│   │   │   └── UserController.php
│   │   └── Requests/             # Form request validation
│   │       ├── Comment/
│   │       ├── IssueLabel/
│   │       ├── Project/
│   │       └── ...
│   ├── Models/                   # Eloquent models
│   │   ├── Comment.php
│   │   ├── Issue.php
│   │   ├── IssueLabel.php
│   │   ├── Label.php
│   │   ├── Project.php
│   │   ├── ProjectUser.php
│   │   └── User.php
│   └── Services/                 # Business logic layer
│       ├── Comment/
│       ├── Issue/
│       ├── Issues/
│       └── Project/
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
│       ├── CommentSeeder.php
│       ├── DatabaseSeeder.php
│       ├── IssueSeeder.php
│       ├── LabelSeeder.php
│       ├── ProjectSeeder.php
│       └── UserSeeder.php
├── routes/
│   └── api.php                   # API routes
├── .env.example                  # Environment template
├── API_TESTING_SCENARIOS.md      # Complete API documentation
└── README.md                     # This file
```

---

## 🗃 Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `timestamps`

### Projects Table
- `id` - Primary key
- `name` - Project name
- `description` - Project description
- `code` - Unique project code (uppercase)
- `timestamps`

### Issues Table
- `id` - Primary key
- `title` - Issue title
- `description` - Issue description
- `status` - Issue status (open, in_progress, completed)
- `priority` - Issue priority (lowest, low, medium, high, highest)
- `code` - Unique issue code (PROJECT-NUMBER)
- `due_window` - JSON field for due date and reminder
- `status_change_at` - Timestamp of last status change
- `project_id` - Foreign key to projects
- `created_by` - Foreign key to users (creator)
- `assigned_to` - Foreign key to users (assignee)
- `timestamps`

### Labels Table
- `id` - Primary key
- `name` - Unique label name
- `color` - Hex color code
- `timestamps`

### Comments Table
- `id` - Primary key
- `body` - Comment text
- `user_id` - Foreign key to users
- `issue_id` - Foreign key to issues
- `timestamps`

### Project_User Table (Pivot)
- `id` - Primary key
- `project_id` - Foreign key to projects
- `user_id` - Foreign key to users
- `role` - User role (developer, manager, tester)
- `contribution_hours` - Hours contributed
- `last_activity` - Last activity timestamp
- `timestamps`

### Issue_Label Table (Pivot)
- `id` - Primary key
- `issue_id` - Foreign key to issues
- `label_id` - Foreign key to labels
- `timestamps`

---

## 🔐 Authentication

This application uses JWT (JSON Web Tokens) for authentication.

### Login Flow

1. **Register or Login** to receive a JWT token
2. **Include the token** in the Authorization header for all protected routes
3. **Token expires** after the configured time (default: 60 minutes)
4. **Refresh token** or login again when expired

### Example Authentication

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@issuetracker.com",
    "password": "password123"
  }'

# Use the token
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🧪 Testing

### Manual Testing with cURL

```bash
# Test login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@issuetracker.com","password":"password123"}'

# Test getting projects (replace TOKEN)
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer TOKEN"
```

### Testing with Postman

1. Import the API endpoints into Postman
2. Create an environment with variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: (set after login)
3. Use `{{base_url}}` and `{{token}}` in your requests

### Complete Testing Scenarios

See **[API_TESTING_SCENARIOS.md](API_TESTING_SCENARIOS.md)** for:
- Step-by-step testing workflows
- Complete endpoint examples
- Expected responses
- Error handling scenarios

---

## 📊 Seeded Data

After running `php artisan db:seed`, you'll have:

### Users (11 total)
- **Admin**: admin@issuetracker.com
- **Team Members**: 
  - john.doe@issuetracker.com
  - jane.smith@issuetracker.com
  - mike.johnson@issuetracker.com
  - sarah.williams@issuetracker.com
  - david.brown@issuetracker.com
  - emily.davis@issuetracker.com
  - chris.wilson@issuetracker.com
  - lisa.anderson@issuetracker.com
  - tom.martinez@issuetracker.com
  - anna.taylor@issuetracker.com

**All passwords**: `password123`

### Projects (5 total)
- E-Commerce Platform (ECOM)
- Mobile Banking App (MBANK)
- CRM System (CRM)
- Healthcare Portal (HEALTH)
- Learning Management System (LMS)

### Labels (15 total)
bug, feature, enhancement, documentation, urgent, help wanted, good first issue, duplicate, wontfix, security, performance, ui/ux, backend, frontend, database

### Issues
- 5-8 issues per project
- Various statuses and priorities
- Realistic titles and descriptions
- Multiple labels per issue

### Comments
- 2-6 comments per issue
- Realistic developer conversations

---

## 🎨 Advanced Features

### Custom Enums

The application uses PHP 8.2+ enums for type safety:

```php
// Status Enum
enum StatusType: string {
    case Open = 'open';
    case Inprogress = 'in_progress';
    case Completed = 'completed';
}

// Priority Enum
enum PriorityType: string {
    case Highest = 'highest';
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';
    case Lowest = 'lowest';
}
```

### Custom Casts

Advanced attribute casting for complex data types:

```php
// Issue Model
protected $casts = [
    'status' => StatusCast::class,
    'priority' => PriorityCast::class,
    'due_window' => DueWindowCast::class,
];
```

### Query Scopes

Reusable query scopes for common filters:

```php
// Get open issues
Issue::open()->get();

// Get urgent issues
Issue::urgent()->get();

// Get projects with most open issues
Project::mostOpen()->get();
```

### Service Layer

Business logic is separated into service classes:

- `IssueService` - Issue management logic
- `IssueLabelService` - Label attachment logic
- `ProjectService` - Project and user management
- `CommentService` - Comment operations

---

## 🔧 Troubleshooting

### Common Issues

#### 1. "Class 'JWT' not found"

```bash
composer require tymon/jwt-auth
php artisan jwt:secret
```

#### 2. Database Connection Error

Check your `.env` file database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_issue_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 3. "Token could not be parsed"

Make sure you're including the token correctly:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

#### 4. Migration Errors

Reset and re-run migrations:
```bash
php artisan migrate:fresh --seed
```

#### 5. Permission Errors

On Linux/Mac, set proper permissions:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache

If you encounter unexpected behavior:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 📞 Support

For issues, questions, or contributions, please:
- Open an issue on GitHub
- Contact: your-email@example.com

---

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- JWT Authentication by [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- Inspired by GitHub Issues and Jira

---

**Happy Coding! 🚀**
