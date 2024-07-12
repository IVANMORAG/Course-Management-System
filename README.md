# Course Management System

![Logo](https://github.com/IVANMORAG/IVANMORAG/blob/main/cursos/cursos.png)

## Overview

The Course Management System is a comprehensive application designed to facilitate the management of courses, including functionalities to add, edit, and delete courses. This system uses PHP for backend operations and MySQL for efficient data management, with XAMPP as the local development environment. The application also supports Navicat for database management.

## Features

- **Admin Section**: 
  - Add, edit, and delete courses.
  - Manage user information and roles.
  - Enable or disable courses as needed.
- **User Section**:
  - View and interact with courses.
  - Take quizzes and earn certificates.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Development Environment**: XAMPP
- **Database Management**: Navicat

## Installation

To run this project locally using XAMPP and Navicat, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/IVANMORAG/Course-Management-System.git
    cd admin-course-system
    ```

2. Move the project to the XAMPP `htdocs` directory:
    ```bash
    mv Course-Management-System /path/to/xampp/htdocs/
    ```

3. Import the database:
    - Open phpMyAdmin from the XAMPP control panel or use Navicat.
    - Create a new database named `course_management`.
    - Import the `course_management.sql` file located in the project directory.

4. Configure the database connection:
    - Open `config.php` in the project directory and update the database credentials:
        ```php
        <?php
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'course_management';
        ?>
        ```

5. Start Apache and MySQL from the XAMPP control panel.

6. Open your browser and go to `http://localhost/Course-Management-System`.

## Usage

1. Use the admin credentials to log in and access the course management functionalities.
2. Navigate through the application to add, edit, and delete courses as needed.
3. Users can log in to view courses, take quizzes, and obtain certificates.


## Contributing

If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
