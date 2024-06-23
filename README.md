# School Management System

The School Management System is a PHP-based web application designed to facilitate the management of students and classes within a school setting. It provides essential CRUD (Create, Read, Update, Delete) operations for both students and classes, along with image upload functionality for student profiles.

## Features

- **Home Page (`index.php`)**:
    - Lists all students with their details (name, email, class, image thumbnail).
    - Allows viewing, editing, and deleting student records.

- **Create Student (`create.php`)**:
    - Form to add a new student with fields for name, email, address, class (dropdown), and image upload.
    - Validates input fields and image format (supports JPG and PNG).
    - Handles image upload with unique filenames to prevent overwriting.

- **View Student (`view.php`)**:
    - Displays comprehensive details of a selected student, including name, email, address, class, image, and creation date.
    - Retrieves class name through SQL JOIN for better data presentation.

- **Edit Student (`edit.php`)**:
    - Form pre-populated with current student details for easy modification.
    - Supports updating student information and image replacement with validation checks.

- **Delete Student (`delete.php`)**:
    - Confirms deletion of a student record.
    - Deletes associated image file from the server if present and removes the student from the database.

- **Manage Classes (`classes.php`)**:
    - Provides functionalities to view, add, edit, and delete classes.
    - Offers a straightforward form for adding new classes with minimal effort.

- **Image Upload Handling**:
    - Images are stored in the `uploads` directory.
    - Validates file types (JPG, PNG) and ensures unique filenames to prevent conflicts.

## How to Setup

1. **Prerequisites**:
    - Install XAMPP with Apache, MySQL, and PHP support.

2. **Database Setup**:
    - Create a MySQL database named `{{school_db}}`.
    - Import the provided `database.sql` file to set up the `student` and `classes` tables.

3. **Project Setup**:
    - Clone this repository or download the ZIP archive and extract it into your web server's document root (e.g., `htdocs` for XAMPP).

4. **Configuration**:
    - Update the database connection details in `db.php` with your MySQL server's hostname, username, password, and database name.

5. **Run the Application**:
    - Start XAMPP and ensure Apache and MySQL services are running.
    - Open a web browser and navigate to `http://localhost/{{school_management_system}}` to access the application.

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS (Bootstrap for styling)

