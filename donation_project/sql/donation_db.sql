-- Database: donation_db

CREATE DATABASE IF NOT EXISTS donation_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE donation_db;

-- Table structure for table `users`
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100),
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  mobile VARCHAR(15),
  gender ENUM('male','female','other'),
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for table `donations`
CREATE TABLE donations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL,
  mobile VARCHAR(15),
  amount DECIMAL(10,2),
  item_type VARCHAR(100),
  description TEXT,
  quantity INT,
  delivery_option VARCHAR(50),
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table structure for table `suggestions`
CREATE TABLE suggestions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  suggestion TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample data for testing
INSERT INTO users (first_name, last_name, email, password, mobile, gender, address) VALUES
('John', 'Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'male', '123 Main St, City');

INSERT INTO donations (user_id, name, email, mobile, amount, item_type, description, quantity, delivery_option, address) VALUES
(1, 'John Doe', 'john@example.com', '9876543210', 50.00, 'Books', 'Used textbooks for children', 10, 'Request Pick-up', '123 Main St, City');