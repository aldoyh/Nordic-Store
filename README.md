# 🌀 Nordic Store - Instagram-to-E-Commerce Marketplace

> Transform Instagram profiles into fully functional online storefronts in seconds.

A dynamic, multi-vendor e-commerce platform where Instagram creators can instantly monetize their content by turning images into sellable products.

## ✨ Key Features

### 🏪 For Customers
- Browse multiple creator shops
- Shop by Instagram influencer (e.g., `/shop/cristiano`)
- Responsive shopping cart with session persistence
- Secure checkout with shipping address collection
- Order confirmation with tracking
- Fully mobile-responsive design

### 📸 For Vendors
- **One-Click Shop Setup**: Enter Instagram username, we fetch all public images
- **Automatic Product Creation**: Each image becomes a product (default £9.99)
- **Flexible Pricing**: Edit prices per product
- **Multi-Vendor Support**: Manage unlimited Instagram accounts as separate shops
- **Vendor Dashboard**: See shop stats, manage orders, track revenue
- **Easy Sharing**: Shop URLs like `shop/username` for social promotion

### 🎯 Architecture Highlights
- **Multi-Vendor**: One vendor can own multiple shops (different Instagram accounts)
- **Instagram Integration**: Scrape public Instagram profiles without API approval
- **Session-Aware Cart**: Works for guests via sessions, converts to user cart on login
- **Nordic Minimalist Design**: Beautiful Tailwind CSS UI with monochrome aesthetic

## 🚀 Quick Start

### Prerequisites
- PHP 8.0+
- Composer
- SQLite (default) or MySQL

### Installation

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Setup database
php artisan migrate
php artisan storage:link

# 4. Start development server
php artisan serve
```

Visit **http://localhost:8000** → Click **Register** to create your vendor account.

## 📋 How It Works

### Customer Experience
```
Marketplace (/) 
  → Browse shops 
  → Click shop 
  → View products 
  → Add to cart 
  → Checkout 
  → Shipping form 
  → Payment 
  → Order confirmation ✓
```

### Vendor Experience
```
Register 
  → Dashboard 
  → "Create Shop" 
  → Enter Instagram username (e.g., "cristiano") 
  → App fetches all public images 
  → Products created automatically 
  → Edit prices 
  → Share: shop/cristiano 
  → Manage multiple shops ✓
```

## 🗂️ Project Structure

```
app/
├── Models/
│   ├── User.php              # Vendor accounts (hasMany shops)
│   ├── Shop.php              # Instagram-connected storefront
│   ├── Product.php           # Images from Instagram
│   ├── Cart.php & CartItem.php
│   ├── Order.php & OrderItem.php
│   └── InstagramSyncLog.php  # Sync history
├── Services/
│   ├── InstagramService.php      # Scrape Instagram
│   ├── ImageDownloadService.php  # Download & optimize
│   └── CartService.php           # Shopping logic
└── Http/Controllers/
    ├── MarketplaceController.php
    ├── ShopController.php
    ├── CartController.php
    ├── CheckoutController.php
    ├── InstagramController.php
    ├── DashboardController.php
    └── Auth/

resources/views/
├── marketplace/index.blade.php    # All shops
├── shop/show.blade.php            # Shop storefront
├── cart/show.blade.php            # Shopping cart
├── checkout/
│   ├── shipping.blade.php         # Address form
│   ├── payment.blade.php          # Payment
│   └── confirmation.blade.php     # Receipt
├── instagram/create.blade.php     # Create shop
├── auth/register.blade.php
├── auth/login.blade.php
└── dashboard/index.blade.php      # Vendor dashboard
```

## 🔌 Database Schema

### Multi-Vendor Architecture
```sql
users
  └─ shops (user_id) — One user → Many shops
      ├─ products (shop_id)
      ├─ carts (shop_id)
      ├─ orders (shop_id)
      └─ instagram_sync_logs (shop_id)
```

**Key Tables:**
- **shops**: `id, user_id, instagram_username, shop_name, instagram_fetch_status, is_active`
- **products**: `id, shop_id, title, description, price, image_path, is_available`
- **carts**: `id, shop_id, user_id, session_id, total_price` (shop-scoped)
- **orders**: `id, shop_id, user_id, order_number, status, total_price, customer_name, delivery_address`

## 📸 Instagram Integration

### How It Works

1. **Vendor enters Instagram username** (e.g., `cristiano`)
2. **InstagramService**:
   - Validates username format
   - Fetches Instagram profile page HTML
   - Extracts image URLs from embedded JSON
   - Returns up to 50 most recent public images
3. **ImageDownloadService**:
   - Downloads each image
   - Stores at `storage/app/public/products/{shop_id}/`
   - Retries up to 3 times with backoff
   - Adds 1-second delay between downloads (rate limiting)
4. **Products Created Automatically**:
   - One product per image
   - Title from Instagram caption
   - Default price: £9.99
   - Ready for sale immediately

### Error Handling
- Private profile → "Profile is private" message
- No images found → "No images in this profile" message
- Network error → Automatic retry with backoff

## 🎨 Design System

**Nordic Minimalist Theme**
- Monochrome palette (black, white, grays)
- Work Sans typography
- Tailwind CSS utilities
- Hover effects: scale (1.02x) + shadow
- Responsive grid: 1 col (mobile) → 3 cols (tablet) → 4 cols (desktop)

## 💳 Payment Processing

**Current Status**: Demo payment form (hardcoded test card)

To integrate Stripe:

```php
// 1. Install package
composer require stripe/stripe-php

// 2. Add to .env
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

// 3. Update CheckoutController::storePayment()
$intent = Stripe::paymentIntents()->create([...])

// 4. Handle webhook
POST /webhooks/stripe
```

## 🧪 Testing

### Create Test Data

```bash
php artisan tinker
```

```php
$user = User::factory()->create(['email' => 'test@example.com']);
$shop = $user->shops()->create([
    'instagram_username' => 'testshop',
    'shop_name' => '@testshop',
    'is_active' => true,
]);
$shop->products()->create([
    'title' => 'Test Product',
    'price' => 9.99,
    'image_path' => 'products/1/test.jpg',
]);
```

### Test Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/` | GET | Marketplace (list all shops) |
| `/register` | GET/POST | Vendor registration |
| `/login` | GET/POST | Sign in |
| `/shop/{username}` | GET | Shop storefront |
| `/shop/{username}/cart` | GET | Shopping cart |
| `/shop/{username}/checkout/shipping` | GET/POST | Shipping form |
| `/shop/{username}/checkout/payment` | GET/POST | Payment |
| `/dashboard` | GET | Vendor dashboard (requires auth) |
| `/instagram/create` | GET/POST | Connect Instagram (requires auth) |

## 🔐 Security Features

- ✅ CSRF protection (Laravel middleware)
- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ Session security
- ✅ User authentication & authorization

## 📦 Production Considerations

### Image Storage
**Currently**: Local filesystem (`storage/app/public/`)

**For Production**: Use cloud storage
```php
// .env
FILESYSTEM_DISK=s3

// app/Services/ImageDownloadService.php
Storage::disk('s3')->put($path, $content);
```

Supported: AWS S3, Google Cloud, Azure Blob, DigitalOcean Spaces

### Database
**Development**: SQLite
**Production**: PostgreSQL or MySQL

### Environment Variables

```bash
APP_NAME="Nordic Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nordic-store.com

DB_CONNECTION=mysql
DB_HOST=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

## 🚀 Deployment

### Heroku
```bash
git push heroku main
heroku run "php artisan migrate"
heroku run "php artisan storage:link"
```

### Docker
```dockerfile
FROM php:8.1-fpm
COPY . /var/www
WORKDIR /var/www
RUN composer install
RUN php artisan migrate
```

## 🛠️ Development Commands

```bash
# Migrations
php artisan migrate              # Run
php artisan migrate:rollback    # Undo
php artisan migrate:fresh       # Reset

# Database
php artisan tinker              # Interactive shell
php artisan db:seed             # Seed test data

# Cache
php artisan cache:clear
php artisan config:clear

# Routes
php artisan route:list          # See all routes

# Tests
php artisan test                # Run test suite
```

## 📈 Roadmap (MVP+)

- [ ] Stripe live payment integration
- [ ] Email order confirmations & shipping notifications
- [ ] Image optimization (WebP, multiple sizes)
- [ ] Product search & advanced filtering
- [ ] Review & ratings system
- [ ] Admin dashboard (manage all shops)
- [ ] Coupon codes & discounts
- [ ] Analytics & revenue dashboards
- [ ] Seller payouts
- [ ] Bulk product management
- [ ] Instagram API integration (official, as alternative to scraping)
- [ ] Mobile app (React Native)

## ⚠️ Important Notes

### Instagram Scraping
- ✅ Works on **public Instagram profiles only**
- ✅ No API approval needed
- ⚠️ Respects rate limits (1-second delays)
- ⚠️ May break if Instagram changes HTML structure
- 💡 Alternative: Switch to [Instagram Graph API](https://developers.facebook.com/docs/instagram-graph-api) (requires business account)

### Rate Limiting
- 1-second delay between image downloads (Instagram-friendly)
- Max 50 images per sync (can adjust in `InstagramService`)
- Retry logic: 3 attempts with exponential backoff

## 📄 License

MIT License

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📞 Support

- Check [Issues](../../issues) for common problems
- Create new issue with detailed description
- Include error messages and reproduction steps

---

**Built with ❤️ using Laravel 13 + Tailwind CSS**

*Start your Instagram-to-E-Commerce journey today!* 🌀
