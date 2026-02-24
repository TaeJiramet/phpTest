# Bangkok Marathon 2025 Registration System

A comprehensive PHP application for managing marathon registration with CRUD operations.

## Features

- View all registered runners
- Register new runners
- Edit existing registrations
- Delete registrations
- Comprehensive personal information collection
- Emergency contact information
- Race details management (distance, t-shirt size)
- Responsive UI with Bootstrap 5
- Form validation
- SQL injection prevention

## Data Fields

- Personal Information: Full name, email, phone, gender, birth date
- Emergency Contact: Emergency contact name and phone
- Race Details: Distance selection, t-shirt size

## Improvements Made

1. **Security Enhancements**:
   - Added input sanitization
   - Implemented prepared statements to prevent SQL injection
   - Added proper validation for all inputs
   - Added unique constraint on email field

2. **Code Organization**:
   - Created utility functions in `utils.php`
   - Improved error handling
   - Better code structure and readability

3. **User Experience**:
   - Added proper error messages
   - Enhanced form validation
   - Improved feedback for user actions
   - Well-organized form sections

4. **Database Management**:
   - Automatic database and table creation
   - Proper character encoding (UTF-8)
   - Unique email constraint to prevent duplicates

## File Structure

- `index.php` - Main page to view all registered runners
- `add.php` - Form to register new runners
- `edit.php` - Form to edit existing registrations
- `delete.php` - Confirmation page for deleting registrations
- `db.php` - Database connection and setup
- `utils.php` - Common utility functions
- `testdb.php` - Simple database connection test

## Requirements

- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server

## Installation

1. Place all files in your web server directory (e.g., htdocs for XAMPP)
2. Ensure your MySQL server is running
3. Access the application through your browser
4. The database and table will be created automatically on first access