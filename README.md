# Course Management System

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
    git clone https://github.com/IVANMORAG/admin-course-system.git
    cd admin-course-system
    ```

2. Move the project to the XAMPP `htdocs` directory:
    ```bash
    mv admin-course-system /path/to/xampp/htdocs/
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

6. Open your browser and go to `http://localhost/admin-course-system`.

## Usage

1. Use the admin credentials to log in and access the course management functionalities.
2. Navigate through the application to add, edit, and delete courses as needed.
3. Users can log in to view courses, take quizzes, and obtain certificates.

## General Project Schema

1. Home
2. Registration and Login
3. Transporter Profile
4. Online Training
5. Performance Evaluation
6. Mental Health Assessment
7. Vehicle Condition Record
8. Notifications and Communication
9. Data Security
10. Availability and Performance
11. Usability
12. Regulatory Compliance
13. Hardware and Software Integration

## Implementation of Functional Requirements

### 1. Transporter Registration and Profile
- **Registration**:
  - Registration form with email and password fields (minimum 8 characters, combination of letters and numbers).
  - Profile form divided into three parts: personal information, driving license details, and work experience.
- **Profile**:
  - Profile page where transporters can update personal information, license, and work experience.

### 2. Online Training
- **Course Access**:
  - Section dedicated to training courses with access to educational materials and exams.
  - Progress tracking for each course and module.
  - Certificate generation upon completion.
- **Assessments**:
  - Assessments at the end of each module.
  - Periodic assessments based on user progress.

### 3. Performance Evaluation
- **Evaluation Criteria**:
  - System to evaluate route efficiency and compliance with safety standards.
  - Results available to transporters and supervisors.

### 4. Mental Health Assessment
- **Questionnaires**:
  - Regular and confidential questionnaires.
  - Resources and support for transporters showing signs of stress or mental health issues.

### 5. Vehicle Condition Record
- **Detailed Record**:
  - Functionality to record the condition of trucks, including damages, tire changes, maintenance, and repairs.
  - Automatic alerts for preventive and corrective maintenance.

### 6. Notifications and Communication
- **Communication**:
  - Messaging system between transporters and supervisors.
  - Automated notifications for training dates, evaluations, and maintenance reminders.

## Contributing

If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
