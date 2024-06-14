CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    department ENUM('BIT Department', 'BSIT Department', 'CPE Department') NOT NULL
    date_of_birth DATE NOT NULL
);