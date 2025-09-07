# Product Images Directory

This directory stores uploaded product images. The following sample images are referenced in the database:

## Sample Images (Referenced in database_schema.sql)
- `mens_suit_black.jpg` - Classic Men's Suit
- `womens_dress_red.jpg` - Women's Evening Dress  
- `blazer_navy.jpg` - Business Blazer
- `jeans_blue.jpg` - Casual Jeans
- `handbag_brown.jpg` - Leather Handbag

## Image Requirements
- **Formats**: JPEG, PNG, GIF, WebP
- **Max Size**: 5MB per image
- **Recommended Dimensions**: 500x500px or larger
- **Aspect Ratio**: Square (1:1) preferred for consistent display

## File Naming Convention
- Use descriptive names: `product_category_color.jpg`
- Avoid spaces and special characters
- Use lowercase with underscores

## Upload Process
1. Admin logs into dashboard
2. Uses "Add New Product" form
3. Selects image file via file input
4. System validates and stores image
5. Unique filename generated automatically

## Directory Permissions
- Ensure this directory has write permissions (755)
- Web server must be able to create and modify files here

## Sample Images
To get started quickly, you can add sample images with these names:
- mens_suit_black.jpg
- womens_dress_red.jpg  
- blazer_navy.jpg
- jeans_blue.jpg
- handbag_brown.jpg

Or use the admin dashboard to upload your own product images.
