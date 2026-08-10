# Balmed 18 — Vanilla PHP E-commerce

This is a complete mobile-first Balmed 18 e-commerce foundation built from scratch around the supplied reference and project brief.

## Stack

- PHP 8+
- MySQL
- PDO prepared statements
- PHP sessions
- HTML5
- CSS3
- Vanilla JavaScript
- Reusable PHP includes/components

## Setup

1. Put the `balmed18` folder in your XAMPP/WAMP web root.
2. Import `database/balmed18.sql` into MySQL/phpMyAdmin.
3. Open `config/config.php`.
4. Set the database credentials if your local MySQL credentials differ.
5. If using XAMPP with `htdocs/balmed18`, keep:

```php
define('BASE_URL', '/balmed18');
```

6. If deploying at the domain root, change it to:

```php
define('BASE_URL', '');
```

7. Make sure `uploads/products/` is writable by PHP.

## URLs

Storefront:

`http://localhost/balmed18/`

Admin:

`http://localhost/balmed18/admin/login.php`

## Default admin

Email:

`admin@balmed18.com`

Password:

`Admin@123`

Change this before deploying publicly.

## Dynamic product rule

Products are NOT hard-coded into the storefront.

The storefront queries active products from MySQL. The admin dashboard controls the product catalogue:

- Add product → automatically appears on the storefront.
- Deactivate product → disappears from the storefront.
- Reactivate product → appears again.
- Change stock → cart and checkout use the current stock.
- Edit product → public product information changes.
- Delete product → product is removed while existing order item snapshots remain.

## Admin features

- Session-based admin authentication
- Dashboard metrics
- Product CRUD
- Product image uploads
- Featured products
- Active/inactive product status
- Category CRUD
- Order listing
- Order details
- Order status updates
- CSRF protection
- Server-side validation
- Prepared SQL statements
- Escaped output
- MIME-validated image uploads

## Storefront features

- Responsive mobile-first homepage
- Dynamic product collection
- Dynamic categories
- Product detail page
- PHP session cart
- Add/remove/update quantities
- Stock limits
- Checkout
- Kenyan customer details
- Delivery location/instructions
- Delivery fee
- Transaction-safe stock reduction
- Order success page
- M-Pesa-ready checkout structure

## Reference/design direction

The implementation follows the supplied brief's visual direction: warm cream/beige surfaces, deep brown branding, muted green accents, serif display headings, rounded product cards, large imagery, spacious sections and a premium natural skincare/wellness feel.

The reference products themselves are not copied into the PHP storefront. Products come from MySQL.

## Product images

Upload product images from:

`Admin → Products → Add product`

Files are stored under:

`uploads/products/`

The database stores the image filename/path rather than binary image data.

Accepted:

- JPG/JPEG
- PNG
- WebP

Maximum size:

5MB.

## Responsive targets

Test at:

- 320px
- 375px
- 390px
- 414px
- 768px
- 1024px
- 1280px+

The storefront and admin UI are designed to avoid horizontal scrolling.

## Next production steps

1. Replace the placeholder hero illustration with the final Balmed 18 brand photography.
2. Upload the actual product images through the admin dashboard.
3. Add the real Balmed 18 contact details.
4. Connect M-Pesa/STK Push to the existing checkout flow.
5. Add transactional email/SMS notifications if required.
6. Change the seeded admin password.
7. Set production database credentials and HTTPS.
