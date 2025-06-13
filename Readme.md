📱 USSD Marks Management System
This project is a PHP-based USSD application for managing student marks and handling appeal processes. It features distinct roles for Admins and Students with menu-driven logic using a mobile phone interface.

🚀 Features
👨‍🎓 Student Functionality
Enter student ID to access the system.

Menu Options:

Check Marks
→ Displays all registered marks:
Your marks for the module:
Module Name: Marks

Appeal My Marks
→ Lists modules with marks and allows appealing with reason
→ Please provide a brief reason for your appeal

Exit

🛠 Admin Functionality
Authenticated using phone number (must exist in admins table).

Menu Options:

View Pending Appeals
→ Lists all pending appeals with basic info.

Update Appeal Status
→ Update appeal to "Under Review" or "Resolved".

Register Student Marks
→ Input student info, module, and score.

Update Existing Marks
→ Change a student's module mark.

Exit

🧱 Tech Stack
Language: PHP

Database: MySQL

USSD Gateway: Africa's Talking

Server: Apache (via XAMPP or similar)

📂 Database Schema
students(student_id, name)

modules(module_id, module_name)

marks(student_id, module_id, mark)

admins(phone)

appeals(appeal_id, student_id, module_id, reason, status)

⚙️ Setup Instructions
Clone the repo

bash
Copy
Edit
git clone https://github.com/your-username/ussd-studentAppeal-portal.git
cd ussd-marks-system
Import MySQL Database

Use the provided SQL file or create the schema manually using the structure above.

Configure Database Connection

Update DB connection details in your PHP script (host, username, password, database).

Expose PHP endpoint to the internet

Use Ngrok or deploy online to expose ussd.php to your USSD provider.

Register endpoint with USSD provider

Example: On Africa's Talking, set your application callback URL to:

arduino
Copy
Edit
https://d7a3-197-157-186-21.ngrok-free.app/Appeal/
📸 Sample USSD Flow
Student
markdown
Copy
Edit
Welcome Student. Enter your Student ID:
> 22RP00490

Select Option:
1. Check Marks
2. Appeal My Marks
3. Exit
Admin
markdown
Copy
Edit
Welcome Admin. Select Option:
1. View Pending Appeals
2. Update Appeal Status
3. Register Student Marks
4. Update Existing Marks
5. Exit
📧 Contact
Created by Niyonshuti Jean De Dieu
For support or contributions, contact: jadowacu@gmai.com