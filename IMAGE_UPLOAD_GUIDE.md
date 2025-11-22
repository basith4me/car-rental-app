# Image Upload Feature Guide

## Overview

The car rental system now includes full image upload functionality for car listings. Images are stored with their complete file paths in the database for educational purposes.

## Features

✅ Upload car images when adding new cars  
✅ Update car images when editing existing cars  
✅ Image preview before upload  
✅ File validation (type and size)  
✅ Automatic file naming to prevent conflicts  
✅ Secure upload directory  
✅ Display images throughout the system  

## Supported Image Formats

- **JPG/JPEG**
- **PNG**
- **GIF**

## File Size Limit

- Maximum: **5MB** per image

## How to Upload Images

### Adding a New Car with Image

1. Login as **admin**
2. Go to **"Manage Cars"**
3. Click **"Add New Car"**
4. Fill in all car details
5. Click **"Choose File"** in the **"Car Image"** field
6. Select an image from your computer
7. You'll see a **preview** of the selected image
8. Click **"Add Car"** to save

### Editing Car Image

1. Login as **admin**
2. Go to **"Manage Cars"**
3. Click the **edit** icon next to any car
4. You'll see the **current image** (if exists)
5. To change the image:
   - Click **"Choose File"**
   - Select a new image
   - The old image will be automatically deleted
6. To keep the current image:
   - Leave the file field empty
7. Click **"Update Car"**

## How Images Are Stored

### Database Storage
- **Column**: `image` (VARCHAR 255)
- **Format**: Full relative path
- **Example**: `uploads/cars/car_673492a1b2c3d.jpg`

### File System Storage
- **Location**: `car-rental-system/uploads/cars/`
- **Naming**: Unique identifier + original extension
- **Example**: `car_673492a1b2c3d.jpg`

### Path Structure
```
car-rental-system/
├── uploads/
│   ├── .htaccess (security)
│   ├── index.php (prevent listing)
│   └── cars/
│       ├── index.php (prevent listing)
│       ├── car_673492a1b2c3d.jpg
│       ├── car_673492a1b2c3e.png
│       └── car_673492a1b2c3f.gif
```

## Security Features

### Upload Validation
- **File type checking**: Only allowed image formats
- **File size checking**: Maximum 5MB
- **File extension validation**: Prevents malicious uploads
- **Unique file naming**: Prevents overwriting

### Directory Protection
- **.htaccess**: Blocks PHP execution in uploads
- **index.php**: Prevents directory listing
- **Proper permissions**: 755 for directories

## Where Images Are Displayed

Images appear in:

1. **Home Page** - Featured cars section
2. **Browse Cars Page** - All car listings
3. **Car Details Page** - Large display with details
4. **Admin Dashboard** - Recent bookings preview (if added)

## Troubleshooting

### Image Not Uploading

**Problem**: "Failed to upload image" error

**Solutions**:
1. Check file size (must be < 5MB)
2. Verify file type (JPG, PNG, GIF only)
3. Ensure `uploads/cars/` directory exists
4. Check directory permissions (755)
5. Verify PHP upload settings in `php.ini`:
   ```ini
   upload_max_filesize = 5M
   post_max_size = 6M
   ```

### Image Not Displaying

**Problem**: Image shows placeholder icon instead

**Solutions**:
1. Check if image file exists in `uploads/cars/`
2. Verify image path in database
3. Check file permissions (644 for images)
4. Clear browser cache
5. Check for typos in image path

### Permission Denied Error

**Problem**: Can't create/upload files

**Solution**: Set correct permissions
```bash
chmod 755 uploads/
chmod 755 uploads/cars/
chmod 644 uploads/cars/*.jpg
```

### Large Images Loading Slowly

**Problem**: Page loads slowly with images

**Solutions**:
1. Compress images before upload
2. Use appropriate image dimensions (800x600 recommended)
3. Convert to JPG for smaller file size
4. Consider image optimization tools

## Manual Image Upload (Alternative Method)

If you prefer to add images manually:

1. **Prepare your image**
   - Rename it (e.g., `my-car.jpg`)
   - Resize if needed (recommended: 800x600px)

2. **Upload via FTP or File Manager**
   - Place image in: `car-rental-system/uploads/cars/`

3. **Add/Edit car via Admin Panel**
   - Leave image upload field empty
   - Manually update database with image path

4. **Update Database Directly** (Advanced)
   ```sql
   UPDATE cars 
   SET image = 'uploads/cars/my-car.jpg' 
   WHERE id = 1;
   ```

## Best Practices

### Image Preparation

1. **Optimize before upload**:
   - Use image compression tools
   - Recommended dimensions: 800x600px or 1024x768px
   - Target file size: < 1MB

2. **Use descriptive names**:
   - Good: `toyota-corolla-2023.jpg`
   - Bad: `IMG_1234.jpg`

3. **Consistent aspect ratio**:
   - Maintain 4:3 or 16:9 ratio
   - Prevents distortion in display

### Security

1. **Never upload**:
   - PHP files disguised as images
   - Files from untrusted sources
   - Very large files (> 5MB)

2. **Regular maintenance**:
   - Remove unused images periodically
   - Check for suspicious files
   - Monitor upload directory size

## Image Management Tips

### Deleting Old Images

When deleting a car:
- Images are NOT automatically deleted
- Manual cleanup required
- Check `uploads/cars/` periodically

### Bulk Upload

For adding many cars with images:
1. Prepare all images in advance
2. Upload images via FTP to `uploads/cars/`
3. Note down filenames
4. Add cars via admin panel
5. Reference uploaded images

### Replacing Images

To replace an image:
1. Edit the car
2. Upload new image
3. Old image is automatically deleted
4. New image takes its place

## Technical Details

### Upload Process Flow

1. User selects image file
2. JavaScript previews image (client-side)
3. Form submits with `enctype="multipart/form-data"`
4. PHP validates file:
   - Checks extension
   - Checks size
   - Checks MIME type
5. PHP generates unique filename
6. PHP moves file to `uploads/cars/`
7. Path saved to database
8. Old image deleted (if editing)

### File Naming Convention

```
Format: car_[unique_id].[extension]
Example: car_673492a1b2c3d.jpg

Where:
- car_ = prefix
- 673492a1b2c3d = unique ID (from uniqid())
- .jpg = original file extension
```

### Database Schema

```sql
CREATE TABLE cars (
    ...
    image VARCHAR(255) DEFAULT NULL,
    ...
);
```

## FAQ

**Q: Can I upload multiple images per car?**  
A: Currently, only one image per car. To add multiple, modify the database schema and upload logic.

**Q: What happens to images when I delete a car?**  
A: The database record is deleted, but the image file remains. Manual cleanup recommended.

**Q: Can customers upload images?**  
A: No, only admins can upload car images.

**Q: Are images backed up?**  
A: No automatic backup. Include `uploads/` directory in your backup routine.

**Q: Can I use images from URLs?**  
A: Not directly. Download the image first, then upload through the system.

**Q: What if uploads folder is deleted?**  
A: Recreate it:
```bash
mkdir -p uploads/cars
chmod 755 uploads uploads/cars
```

## Support

For issues with image uploads:
1. Check this guide first
2. Verify file permissions
3. Check PHP error logs
4. Test with different image
5. Refer to main README.md

---

**Version**: 2.0 with Image Upload  
**Last Updated**: 2024
