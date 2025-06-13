CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20)
);

CREATE TABLE modules (
    module_id VARCHAR(20) PRIMARY KEY,
    module_name VARCHAR(100)
);

CREATE TABLE marks (
    student_id VARCHAR(20),
    module_id VARCHAR(20),
    mark INT,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (module_id) REFERENCES modules(module_id)
);

CREATE TABLE appeals (
    appeal_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20),
    module_id VARCHAR(20),
    reason TEXT,
    status VARCHAR(20) DEFAULT 'Pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    phone VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100)
);
