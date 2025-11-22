# Quick Setup Guide - Car Rental System

## 5-Minute Setup

### Step 1: Install XAMPP
Download and install XAMPP from https://www.apachefriends.org/

### Step 2: Copy Files
Copy the `car-rental-system` folder to:
- **Windows**: `C:\xampp\htdocs\`
- **Mac**: `/Applications/XAMPP/htdocs/`
- **Linux**: `/opt/lampp/htdocs/`

### Step 3: Start Services
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL

### Step 4: Create Database
1. Go to http://localhost/phpmyadmin
2. Click "New" → Name it `car_rental_db` → Click "Create"
3. Click "SQL" tab
4. Open `car-rental-system/database/schema.sql` and copy all content
5. Paste into SQL box and click "Go"

### Step 5: Access Application
Open browser and go to: **http://localhost/car-rental-system/**

## Login Credentials

**Admin:**
- Username: `admin`
- Password: `admin123`

**Customer:**
- Register a new account at the Register page

## That's it! 🎉

Your car rental system is now ready to use!

## Need Help?

See the complete README.md for:
- Detailed installation steps
- Troubleshooting guide
- Feature documentation
- Usage instructions
