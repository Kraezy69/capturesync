-- Create admin table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('Admin', 'Client', 'Photographer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert initial admin user (password will be: admin123)
INSERT INTO users (fullname, email, password, user_type) VALUES 
('System Administrator', 'admin@capturesync.com', '$2y$10$8tPnpGpX0H/Ci9LQs4IQh.3RB1Zg0QI.JNh1qm.QFe0QA3/USH93.', 'Admin');

-- Create activity_log table if it doesn't exist
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(100) NOT NULL,
    action TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 