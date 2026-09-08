🌸 Logic Bloom ERP
Logic Bloom ERP is a web-based Educational Enterprise Resource Planning System designed to simplify and centralize the academic and administrative operations of an educational institution.
The system provides separate modules for Principals, Teachers, and Students, allowing each user to access features relevant to their role. It manages branches, classes, teachers, students, subjects, attendance, marks, results, and salary records through a centralized relational database.

🚀 Features
👨‍💼 Principal Module
- Principal registration and login
- Principal dashboard
- Branch management
- Class and batch management
- Teacher management
- Student management
- Teacher salary management
- Academic overview and statistics
- Principal-specific data isolation

👨‍🏫 Teacher Module
- Teacher authentication
- Teacher dashboard
- Subject management
- Student attendance management
- Marks and examination management
- Academic record management
- Access to assigned academic information

👨‍🎓 Student Module
- Student login
- Personalized student dashboard
- Student profile information
- Marks and examination records
- Academic performance
- Grade calculation
- Attendance percentage
- Recent attendance records

🛠️ Technologies Used
Technology	Purpose
PHP	Backend & server-side logic
MySQL / MariaDB	Database management
HTML5	Web page structure
CSS3	UI styling & responsive design
JavaScript	Client-side interactions
SQL	Database queries
PHP Sessions	Authentication & authorization
Apache	Local web server


🗄️ Database Architecture
Logic Bloom ERP uses a relational database to connect different components of the educational institution.
Main Tables
- principal
- branches
- classes
- teachers
- students
- subjects
- marks
- attendance
- salary_payments
- notifications
The database maintains relationships between principals, branches, classes, teachers, students, subjects, attendance, marks, and salary records.

🔐 Security & Access Control
The system implements:
- Role-based authentication
- PHP session-based authorization
- Password hashing
- Principal-specific data isolation
- User-specific dashboards
- Protected management pages
- Database-level relationships
Each principal's institutional data is scoped using their authenticated principal ID to prevent unrelated institutional records from appearing across accounts.

🎨 User Interface
Logic Bloom ERP uses a modern glassmorphism-inspired interface with:
- Responsive dashboards
- Glass-style cards
- Clean navigation
- Interactive buttons
- Data tables
- Mobile-friendly layouts
- Academic performance indicators

File names may vary depending on the current version of the project.
⚙️ Installation & Setup
1. Clone the Repository
git clone https://github.com/YOUR-USERNAME/Logic-Bloom-ERP.git
cd Logic-Bloom-ERP

2. Setup Database
Create a database named:
shradhay

Import the provided SQL database into MySQL/MariaDB.
Using the MySQL command line:
mysql -u root -p shradhay < database.sql

Or import the SQL file through phpMyAdmin.
3. Configure Database
Update db.php with your local database credentials:
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "shradhay"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

4. Start the Server
For XAMPP:
Start Apache
Start MySQL

Then open:
http://localhost/Logic-Bloom-ERP/

📊 Core Workflow
                 Logic Bloom ERP
                       │
        ┌──────────────┼──────────────┐
        │              │              │
    Principal        Teacher        Student
        │              │              │
        ▼              ▼              ▼
   Management      Academics      Academic View
        │              │              │
   ┌────┼────┐     ┌───┼────┐     ┌───┼────┐
   │    │    │     │   │    │     │   │    │
Branch Class Teacher Subject Marks  Marks Attendance
   │
Students
   │
Salary

💡 Future Enhancements
Possible future improvements include:
- Online fee management
- Timetable management
- Examination scheduling
- Automated notifications
- Email/SMS integration
- Advanced analytics
- PDF report generation
- Student result cards
- Teacher leave management
- Cloud deployment
- Mobile application
- API integration
- Advanced security and audit logs

🎯 Project Objective
The primary goal of Logic Bloom ERP is to digitize and centralize educational institution management by providing a single platform for academic and administrative activities.
It reduces dependence on manual record keeping, improves accessibility to information, and provides different users with role-specific tools.

👨‍💻 Developer
Logic Bloom ERP
Full-Stack Educational ERP Project
Tech Stack:
PHP MySQL/MariaDB HTML5 CSS3 JavaScript SQL

📄 License
This project is developed for educational and portfolio purposes. You may modify and extend it according to your requirements.
