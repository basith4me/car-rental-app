# Car Rental System

A fully functional car rental management system built with HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL.

## Features

### Admin Features
- **Login Authentication**: Secure admin login
- **Add Cars**: Add new vehicles to the fleet with complete details
- **View Bookings**: View all customer bookings with detailed information
- **Manage Users**: View and delete customer accounts
- **Manage Cars**: Edit and delete vehicles from the fleet
- **Dashboard**: Overview of system statistics (total cars, bookings, revenue, etc.)

### Customer Features
- **Register & Login**: Create account and secure login
- **Browse Cars**: View all available cars with filters
- **View Car Details**: Detailed information about each vehicle
- **Book Cars**: Select dates and book vehicles
- **View My Bookings**: See all personal bookings
- **Cancel Bookings**: Cancel confirmed bookings
- **Generate Invoice**: Automatic invoice generation after successful booking
- **Print Invoice**: Print booking invoices

## Technology Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, Bootstrap Icons, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Server**: Apache (XAMPP)

## Project Structure

```
car-rental-system/
├── admin/
│   ├── add-car.php
│   ├── dashboard.php
│   ├── edit-car.php
│   ├── manage-cars.php
│   ├── manage-users.php
│   └── view-bookings.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   └── database.php
├── customer/
│   ├── invoice.php
│   └── my-bookings.php
├── database/
│   └── schema.sql
├── includes/
│   ├── auth.php
│   └── functions.php
├── car-details.php
├── cars.php
├── index.php
├── login.php
├── logout.php
├── register.php
└── README.md
```

## Installation Guide

### Prerequisites

1. **XAMPP** installed on your system
   - Download from: https://www.apachefriends.org/
   - Ensure Apache and MySQL services are installed

### Step 1: Download and Extract

1. Download or clone this project
2. Extract the `car-rental-system` folder

### Step 2: Move Project to XAMPP

1. Navigate to your XAMPP installation directory (usually `C:\xampp` on Windows or `/Applications/XAMPP` on Mac)
2. Open the `htdocs` folder
3. Copy the entire `car-rental-system` folder into `htdocs`
4. Final path should be: `C:\xampp\htdocs\car-rental-system\` (or equivalent on Mac/Linux)

### Step 3: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** service (click Start button)
3. Start **MySQL** service (click Start button)
4. Ensure both services show "Running" status

### Step 4: Create Database

**Option A: Using phpMyAdmin (Recommended)**

1. Open your web browser
2. Go to: `http://localhost/phpmyadmin`
3. Click on **"New"** in the left sidebar to create a new database
4. Enter database name: `car_rental_db`
5. Click **"Create"**
6. Select the `car_rental_db` database from the left sidebar
7. Click on the **"SQL"** tab
8. Open the file: `car-rental-system/database/schema.sql` in a text editor
9. Copy all the SQL code
10. Paste it into the SQL query box in phpMyAdmin
11. Click **"Go"** to execute
12. You should see success messages indicating tables and data were created

**Option B: Using MySQL Command Line**

1. Open Command Prompt (Windows) or Terminal (Mac/Linux)
2. Navigate to MySQL bin directory:
   ```bash
   cd C:\xampp\mysql\bin
   ```
3. Login to MySQL:
   ```bash
   mysql -u root -p
   ```
4. Press Enter (default XAMPP has no password)
5. Run the following commands:
   ```sql
   CREATE DATABASE car_rental_db;
   USE car_rental_db;
   SOURCE C:/xampp/htdocs/car-rental-system/database/schema.sql;
   ```
6. Type `exit` to close MySQL

### Step 5: Configure Database Connection (Optional)

The default configuration should work with standard XAMPP installation. If you need to modify:

1. Open `config/database.php`
2. Modify these settings if needed:
   ```php
   define('DB_HOST', 'localhost');     // Usually localhost
   define('DB_USER', 'root');          // Default XAMPP user
   define('DB_PASS', '');              // Default XAMPP has no password
   define('DB_NAME', 'car_rental_db'); // Database name
   ```

### Step 6: Access the Application

1. Open your web browser
2. Go to: `http://localhost/car-rental-system/`
3. You should see the home page of the Car Rental System

## Default Login Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`

### Customer Account
- You need to register a new account using the "Register" option
- Or use the registration page at: `http://localhost/car-rental-system/register.php`

## Usage Guide

### For Customers

1. **Register Account**:
   - Click "Register" in the navigation
   - Fill in all required information
   - Submit the form
   - Login with your new credentials

2. **Browse and Book Cars**:
   - Click "Browse Cars" to see all available vehicles
   - Use filters to narrow down search
   - Click "View Details" on any car
   - Select start and end dates
   - Click "Book Now"
   - View your invoice

3. **Manage Bookings**:
   - Go to "My Bookings"
   - View all your bookings
   - View or print invoices
   - Cancel bookings if needed

### For Admin

1. **Login**:
   - Use admin credentials to login
   - Access the admin dashboard

2. **Manage Cars**:
   - Add new cars to the system
   - Edit existing car details
   - Delete cars from the system
   - View car availability status

3. **View Bookings**:
   - See all customer bookings
   - Monitor booking status
   - Track rental periods

4. **Manage Users**:
   - View all registered customers
   - Delete user accounts if needed

## Database Schema

### Tables

1. **users**: Stores user information (admin and customers)
2. **cars**: Stores vehicle information
3. **bookings**: Stores rental booking information

## Features Explained

### Booking System
- Date validation prevents past dates
- Availability checking prevents double bookings
- Automatic price calculation based on rental duration
- Real-time total price updates

### Invoice System
- Automatic invoice generation after booking
- Professional invoice layout
- Printable format
- Includes all booking and vehicle details

### Security Features
- Password hashing using PHP's password_hash()
- SQL injection prevention using prepared statements
- Session-based authentication
- Role-based access control (Admin vs Customer)

## Troubleshooting

### Issue: "Access Denied" Database Error
**Solution**: 
- Check database credentials in `config/database.php`
- Ensure MySQL service is running in XAMPP

### Issue: "Page Not Found" 404 Error
**Solution**:
- Ensure project is in correct folder: `htdocs/car-rental-system/`
- Check Apache service is running
- Verify the URL: `http://localhost/car-rental-system/`

### Issue: CSS/Styling Not Loading
**Solution**:
- Clear browser cache
- Check file paths in HTML files
- Ensure Bootstrap CDN is accessible

### Issue: Can't Login
**Solution**:
- Verify database was created correctly
- Check if the default admin user exists in the `users` table
- Try registering a new customer account

### Issue: Bookings Not Saving
**Solution**:
- Ensure you're logged in as a customer (not admin)
- Check if the car is available
- Verify dates are valid (not in the past)

## Browser Compatibility

- Google Chrome (Recommended)
- Mozilla Firefox
- Microsoft Edge
- Safari

## System Requirements

- **XAMPP**: 7.4 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Web Browser**: Modern browser with JavaScript enabled

## Sample Data

The system comes with pre-loaded sample data:
- 1 Admin user
- 5 Sample cars (Toyota Corolla, Honda Civic, Ford Mustang, Tesla Model 3, BMW X5)

## Future Enhancements

Possible features to add:
- Image upload for cars
- Payment gateway integration
- Email notifications
- Advanced search and filters
- User profile management
- Booking history reports
- Revenue analytics
- Customer reviews and ratings
- Multi-language support

## Development Notes

### File Organization
- All admin pages are in the `admin/` folder
- Customer-specific pages are in the `customer/` folder
- Shared functions are in the `includes/` folder
- Database configuration is in the `config/` folder

### Coding Standards
- PHP files use <?php tags
- Functions are well-documented
- SQL queries use prepared statements
- HTML/CSS follows Bootstrap conventions

## Support

For issues or questions:
1. Check the Troubleshooting section
2. Verify all installation steps were completed
3. Ensure XAMPP services are running
4. Check browser console for JavaScript errors

## License

This project is created for educational purposes.

## Credits

- Bootstrap 5 for UI components
- Bootstrap Icons for iconography
- Google Fonts for typography

---

**Version**: 1.0  
**Last Updated**: 2024  
**Author**: Car Rental System Development Team

## Quick Start Checklist

- [ ] XAMPP installed and running
- [ ] Project copied to htdocs folder
- [ ] Database created via phpMyAdmin
- [ ] Schema.sql imported successfully
- [ ] Accessed http://localhost/car-rental-system/
- [ ] Logged in with admin credentials
- [ ] Tested customer registration
- [ ] Tested booking flow
- [ ] Tested invoice generation

If all items are checked, your system is ready to use!
