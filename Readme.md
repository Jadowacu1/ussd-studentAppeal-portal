# 📱 USSD Marks Management System

This project is a PHP-based USSD application for managing student marks and handling appeal processes. It features distinct roles for Admins and Students with menu-driven logic using a mobile phone interface.

---

## 🚀 Features

### 👨‍🎓 Student Functionality
Enter student ID to access the system.

#### Menu Options:
- **Check Marks**
  - Displays all registered marks:
    - `Your marks for the module:`
    - `Module Name: Marks`
- **Appeal My Marks**
  - Lists modules with marks and allows appealing with reason.
  - Asks: `Please provide a brief reason for your appeal`
- **Exit**

---

### 🛠 Admin Functionality
Authenticated using phone number (must exist in `admins` table).

#### Menu Options:
- **View Pending Appeals**
  - Lists all pending appeals with basic info.
- **Update Appeal Status**
  - Update appeal to "Under Review" or "Resolved".
- **Register Student Marks**
  - Input student info, module, and score.
- **Update Existing Marks**
  - Change a student's module mark.
- **Exit**

---

## 🧱 Tech Stack

- **Language:** PHP  
- **Database:** MySQL  
- **USSD Gateway:** Africa's Talking  
- **Server:** Apache (via XAMPP or similar)

---

## 📂 Database Schema

```
students(student_id, name)
modules(module_id, module_name)
marks(student_id, module_id, mark)
admins(phone)
appeals(appeal_id, student_id, module_id, reason, status)
```

---

## ⚙️ Setup Instructions

### 1. Clone the Repo

```bash
git clone https://github.com/your-username/ussd-studentAppeal-portal.git
cd ussd-studentAppeal-portal
```

### 2. Import MySQL Database

Use the provided SQL file or manually create the schema using the structure above.

### 3. Configure Database Connection

Update the database connection details in your PHP script (`host`, `username`, `password`, `database`).

### 4. Expose PHP Endpoint to the Internet

Use [Ngrok](https://ngrok.com/) or any other tunneling service to expose your `ussd.php` file to your USSD provider.

### 5. Register Endpoint with USSD Provider

For example, in Africa's Talking, set your application callback URL to:

```
https://d7a3-197-157-186-21.ngrok-free.app/Marks_Appeal/
```

---

## 📸 Sample USSD Flow

### 👨‍🎓 Student

```
Welcome Student. Enter your Student ID:
> 22RP00490

Select Option:
1. Check Marks
2. Appeal My Marks
3. Exit
```

### 🛠 Admin

```
Welcome Admin. Select Option:
1. View Pending Appeals
2. Update Appeal Status
3. Register Student Marks
4. Update Existing Marks
5. Exit
```

---

## 📧 Contact

**Created by:** Niyonshuti Jean De Dieu  
**Email:** [jadowacu@gmai.com](mailto:jadowacu@gmai.com)
