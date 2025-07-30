# Task 2 - Complete CRUD Blog Application with Authentication

##  Project Overview
A complete PHP and MySQL/SQLite-based web application for managing blog posts with full CRUD operations (Create, Read, Update, Delete) and secure user authentication system.

##  Features Implemented
-  **User Authentication**: Secure registration, login, and logout
-  **Password Security**: Password hashing using PHP's password_hash()
-  **Session Management**: Secure session-based authentication
-  **CRUD Operations**: Complete Create, Read, Update, Delete functionality
-  **User Dashboard**: Personal dashboard for managing posts
-  **Responsive Design**: Mobile-friendly interface
-  **Security**: SQL injection prevention with prepared statements
-  **Input Validation**: Server-side form validation and sanitization
-  **Database Auto-Setup**: Automatic database and table creation
-  **Sample Data**: Demo users and posts for immediate testing

##  Technologies Used
- **Backend**: PHP 8.2+ with PDO
- **Database**: SQLite (portable, no server required)
- **Frontend**: HTML5, CSS3 with responsive design
- **Security**: Prepared Statements, Password Hashing, Session Management

##  Project Structure
`
crud-app-fresh/
 auth/
    login.php          # User login page
    register.php       # User registration page
    logout.php         # Logout handler
 posts/
    create.php         # Create new post
    edit.php           # Edit existing post
    delete.php         # Delete post
 config/
    db.php             # Database configuration & helper functions
 index.php              # Homepage (display all posts)
 dashboard.php          # User dashboard (session-protected)
 style.css              # Application styling
 database_schema.sql    # Database schema with sample data
 test_login.php         # Login testing utility
 create_user.php        # User creation utility
 README.md              # This documentation
`

##  Installation & Setup

### Prerequisites
- PHP 8.0 or higher
- No database server required (uses SQLite)

### Quick Start
1. **Clone the repository**:
   `ash
   git clone https://github.com/reva599/task-2--project-name-.git
   cd task-2--project-name-
   `

2. **Start PHP server**:
   `ash
   php -S localhost:8000
   `

3. **Access application**:
   Open http://localhost:8000 in your browser

4. **Login with demo accounts**:
   - Username: dmin | Password: password
   - Username: 	estuser | Password: password
   - Username: logger | Password: password

##  Demo Accounts
All demo accounts use password: **"password"**
- **admin** - Administrator account with sample posts
- **testuser** - Regular user account
- **blogger** - Content creator account

##  Database Schema
`sql
-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
`

##  Security Features
- **Password Hashing**: Uses PHP's password_hash() with default algorithm
- **SQL Injection Prevention**: All database queries use prepared statements
- **Input Sanitization**: All user inputs are sanitized and validated
- **Session Security**: Secure session management with proper authentication checks
- **Authorization**: Users can only edit/delete their own posts
- **CSRF Protection**: Forms include security tokens

##  Usage Guide

### For Users:
1. **Register**: Create a new account with username and password
2. **Login**: Access your dashboard with credentials
3. **Create Posts**: Write and publish new blog posts
4. **Manage Posts**: Edit or delete your existing posts
5. **View Posts**: Browse all posts on the homepage

### For Developers:
- All database operations use PDO with prepared statements
- Helper functions are available in config/db.php
- Session management is handled automatically
- Form validation includes both client and server-side checks
- SQLite database is created automatically on first run

##  Testing Features

### Manual Testing Checklist:
- [ ] User registration works with validation
- [ ] User login/logout functionality
- [ ] Password hashing and verification
- [ ] Create new posts (authenticated users only)
- [ ] Edit own posts (authorization check)
- [ ] Delete own posts with confirmation
- [ ] View all posts on homepage
- [ ] Dashboard shows user-specific posts
- [ ] Responsive design on mobile devices
- [ ] Security: SQL injection prevention
- [ ] Security: Users cannot edit others' posts

### Test Utilities:
- **test_login.php**: Debug login issues and fix password hashes
- **create_user.php**: Create test users with custom credentials

##  Deployment Options

### Local Development:
`ash
php -S localhost:8000
`

### XAMPP/WAMP:
1. Copy project to htdocs folder
2. Access via http://localhost/crud-app-fresh/

### Production:
1. Upload files to web server
2. Ensure PHP 8.0+ is available
3. Set proper file permissions
4. Database will be created automatically

##  Features Showcase

### Homepage:
- Clean, modern design
- Display all blog posts
- User-friendly navigation
- Responsive layout

### Authentication:
- Secure login/registration forms
- Password strength validation
- Session management
- Demo account information

### Dashboard:
- Personal post management
- Statistics display
- Quick actions (create, edit, delete)
- User-specific content

### CRUD Operations:
- **Create**: Rich text post creation
- **Read**: Beautiful post display
- **Update**: In-place post editing
- **Delete**: Confirmation dialogs

##  Technical Specifications

### Performance:
- SQLite database for fast, portable storage
- Optimized queries with proper indexing
- Minimal resource usage
- Fast page load times

### Security:
- Industry-standard password hashing
- SQL injection prevention
- XSS protection through input sanitization
- Session hijacking prevention

### Compatibility:
- PHP 8.0+
- Modern web browsers
- Mobile responsive
- Cross-platform (Windows, Mac, Linux)

##  ApexPlanet Internship - Task 2 Completion

This project demonstrates mastery of:
-  PHP fundamentals and best practices
-  Database design and operations
-  User authentication and session management
-  CRUD operations implementation
-  Security considerations in web development
-  Responsive web design
-  Code organization and structure
-  Error handling and debugging

##  Support & Troubleshooting

### Common Issues:
1. **Login fails**: Run 	est_login.php to fix password hashes
2. **Database errors**: Delete log.sqlite to recreate database
3. **Permission errors**: Check file permissions on server
4. **PHP errors**: Ensure PHP 8.0+ is installed

### Getting Help:
- Check the test utilities for debugging
- Review error logs for specific issues
- Ensure all files are uploaded correctly

---

**Status**:  Complete | **Version**: 2.0.0 | **Last Updated**: July 2025

**Author**: ApexPlanet Internship Project | **Repository**: https://github.com/reva599/task-2--project-name-
