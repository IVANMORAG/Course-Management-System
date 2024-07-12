# Course Management System

![Logo](https://github.com/IVANMORAG/IVANMORAG/blob/main/Presentacion.png)

## Overview

The Course Management System is a comprehensive application designed to facilitate the management of courses, including functionalities to add, edit, and delete courses. This system uses PHP for backend operations and MySQL for efficient data management, with XAMPP as the local development environment.

## Features

- **Add Courses**: Easily add new courses with detailed information.
- **Edit Courses**: Update existing course details.
- **Delete Courses**: Remove courses that are no longer relevant.
- **User Management**: Manage user information and roles.
- **Course Activation**: Enable or disable courses as needed.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Development Environment**: XAMPP

## Installation

To run this project locally using XAMPP, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/IVANMORAG/admin-course-system.git
    cd admin-course-system
    ```

2. Move the project to the XAMPP `htdocs` directory:
    ```bash
    mv admin-course-system /path/to/xampp/htdocs/
    ```

3. Import the database:
    - Open phpMyAdmin from the XAMPP control panel.
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

6. Open your browser and go to `http://localhost/admin-course-system`.

## Usage

1. Use the admin credentials to log in and access the course management functionalities.
2. Navigate through the application to add, edit, and delete courses as needed.

## Contributing

If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or suggestions, please contact:

- **Name**: Iván Mora
- **Email**: ivan.mora@example.com
- **LinkedIn**: [linkedin.com/in/ivanmora](https://linkedin.com/in/ivanmora)
- **GitHub**: [github.com/IVANMORAG](https://github.com/IVANMORAG)
