-- Create database
CREATE DATABASE IF NOT EXISTS car_rental_db;
USE car_rental_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cars table
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    color VARCHAR(30),
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    seat_capacity INT NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    fuel_type ENUM('Petrol', 'Diesel', 'Electric', 'Hybrid') NOT NULL,
    transmission ENUM('Manual', 'Automatic') NOT NULL,
    image VARCHAR(255),
    status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('admin', 'admin@carrental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin');

-- Insert sample cars
INSERT INTO cars (car_name, brand, model, year, color, registration_number, seat_capacity, price_per_day, fuel_type, transmission, description, image) VALUES
('Toyota Corolla', 'Toyota', 'Corolla', 2023, 'White', 'ABC-1234', 5, 45.00, 'Petrol', 'Automatic', 'Reliable and fuel-efficient sedan perfect for city driving.', 'toyota-corolla.jpg'),
('Honda Civic', 'Honda', 'Civic', 2023, 'Black', 'XYZ-5678', 5, 50.00, 'Petrol', 'Manual', 'Sporty sedan with excellent handling and comfort.', 'honda-civic.jpg'),
('Ford Mustang', 'Ford', 'Mustang', 2022, 'Red', 'MUS-9012', 4, 120.00, 'Petrol', 'Automatic', 'Iconic sports car with powerful performance.', 'ford-mustang.jpg'),
('Tesla Model 3', 'Tesla', 'Model 3', 2023, 'Blue', 'TES-3456', 5, 95.00, 'Electric', 'Automatic', 'Electric vehicle with cutting-edge technology.', 'tesla-model3.jpg'),
('BMW X5', 'BMW', 'X5', 2023, 'Silver', 'BMW-7890', 7, 150.00, 'Diesel', 'Automatic', 'Luxury SUV with spacious interior and premium features.', 'bmw-x5.jpg');
