# Common Tasks Guide

## For Developers

### How to Add a New Feature

1. **Backend (PHP)**:
   - Add function to `includes/functions.php`
   - Use prepared statements for database queries
   - Return success/error arrays

2. **Frontend (HTML/PHP)**:
   - Create new PHP file or modify existing
   - Include necessary files (`auth.php`, `functions.php`)
   - Add navigation link in navbar

3. **Styling**:
   - Add CSS to `assets/css/style.css`
   - Use Bootstrap classes when possible
   - Follow existing naming conventions

### How to Modify Database

1. **Add New Table**:
   ```sql
   CREATE TABLE table_name (
       id INT AUTO_INCREMENT PRIMARY KEY,
       field_name VARCHAR(100),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

2. **Add New Column**:
   ```sql
   ALTER TABLE table_name 
   ADD COLUMN new_field VARCHAR(100);
   ```

3. **Update `schema.sql`** with changes

### How to Add New User Role

1. Modify `users` table role enum
2. Update `includes/auth.php` functions
3. Create new access control function
4. Add role-specific pages

### How to Enable Image Upload

1. Create `uploads/` directory in project root
2. Set proper permissions (755)
3. Modify car forms to include file upload
4. Update `includes/functions.php` to handle uploads
5. Add image validation (size, type)

## For Administrators

### How to Reset Admin Password

**Via phpMyAdmin**:
1. Go to http://localhost/phpmyadmin
2. Select `car_rental_db`
3. Click `users` table
4. Find admin user
5. Edit password field
6. Use online bcrypt generator for new hash
7. Replace password hash

**Via PHP Script**:
```php
<?php
echo password_hash('new_password', PASSWORD_DEFAULT);
?>
```

### How to Backup Database

**Method 1: phpMyAdmin**
1. Go to http://localhost/phpmyadmin
2. Select `car_rental_db`
3. Click "Export" tab
4. Click "Go"
5. Save .sql file

**Method 2: Command Line**
```bash
mysqldump -u root -p car_rental_db > backup.sql
```

### How to Restore Database

**Method 1: phpMyAdmin**
1. Go to http://localhost/phpmyadmin
2. Select `car_rental_db`
3. Click "Import" tab
4. Choose backup file
5. Click "Go"

**Method 2: Command Line**
```bash
mysql -u root -p car_rental_db < backup.sql
```

### How to Clear All Data (Keep Structure)

```sql
TRUNCATE TABLE bookings;
TRUNCATE TABLE cars;
TRUNCATE TABLE users;
-- Then re-insert admin user
```

## For Users

### How to Retrieve Lost Password

Currently not implemented. Options:
1. Contact admin to reset
2. Register new account
3. Implement password recovery feature

### How to Change User Details

1. Login as customer
2. Go to profile (if implemented)
3. Or contact admin

### How to View Past Bookings

1. Login as customer
2. Click "My Bookings"
3. All bookings displayed with status

## Troubleshooting

### Can't Login

1. Check if user exists in database
2. Verify password is correct
3. Check if cookies/sessions enabled
4. Clear browser cache

### Bookings Not Showing

1. Check if logged in as customer (not admin)
2. Verify bookings exist in database
3. Check user_id matches
4. Review PHP error logs

### Cars Not Displaying

1. Check if cars exist in database
2. Verify car status is 'available'
3. Check SQL query in functions.php
4. Review browser console for errors

### Price Not Calculating

1. Check JavaScript is enabled
2. Verify dates are selected
3. Check `main.js` for errors
4. Inspect browser console

### Invoice Not Generating

1. Check booking exists
2. Verify user owns booking
3. Check booking ID in URL
4. Review PHP errors

## Maintenance Tasks

### Regular Maintenance

**Daily**:
- Check booking status
- Monitor active rentals

**Weekly**:
- Backup database
- Review user registrations
- Check system logs

**Monthly**:
- Update car availability
- Review completed bookings
- Clean up cancelled bookings

### Performance Optimization

1. **Database Indexing**:
   ```sql
   CREATE INDEX idx_status ON cars(status);
   CREATE INDEX idx_dates ON bookings(start_date, end_date);
   ```

2. **Enable Caching**:
   - Add PHP OPcache
   - Implement query caching

3. **Optimize Images**:
   - Compress uploaded images
   - Use appropriate formats
   - Implement lazy loading

## Customization

### Change Colors

Edit `assets/css/style.css`:
```css
/* Primary gradient colors */
.navbar {
    background: linear-gradient(135deg, #YOUR_COLOR1 0%, #YOUR_COLOR2 100%);
}
```

### Add New Car Fields

1. Modify `cars` table structure
2. Update `add-car.php` form
3. Update `edit-car.php` form
4. Modify display pages

### Change Site Name

Search and replace "CarRental" in:
- All PHP files
- CSS files
- Database

### Modify Invoice Template

Edit `customer/invoice.php`:
- Change company details
- Modify terms & conditions
- Adjust styling
- Add logo

## Advanced Features

### Add Email Notifications

1. Install PHPMailer
2. Configure SMTP settings
3. Add email function
4. Trigger on booking events

### Add Payment Gateway

1. Choose provider (Stripe, PayPal)
2. Get API credentials
3. Add payment page
4. Update booking flow
5. Store transaction records

### Add Reports

1. Create reports page
2. Add date range filters
3. Generate statistics
4. Export to PDF/Excel

### Add Car Images

1. Create uploads directory
2. Add file upload to forms
3. Store file path in database
4. Display images in views
5. Add image validation

## Security Best Practices

### Regular Updates

- Keep PHP updated
- Update Bootstrap CDN links
- Review security patches
- Monitor for vulnerabilities

### Access Control

- Review user permissions regularly
- Implement strong password policy
- Add rate limiting for login
- Enable HTTPS in production

### Data Protection

- Regular backups
- Encrypt sensitive data
- Sanitize all inputs
- Use prepared statements

## Support Resources

- PHP Documentation: https://www.php.net/docs.php
- Bootstrap Docs: https://getbootstrap.com/docs/
- MySQL Reference: https://dev.mysql.com/doc/
- Stack Overflow: https://stackoverflow.com/

---

For more help, refer to the main README.md file.
