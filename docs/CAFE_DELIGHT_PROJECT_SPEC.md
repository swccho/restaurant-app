# Cafe Delight Ordering Website — Full Project Description

## Project goal

Build a modern restaurant website for **Cafe Delight** where the restaurant can **publish their menu and offers** and customers can **place orders online** (delivery or pickup). The restaurant should be able to **manage orders** and keep the menu updated without needing a developer.

---

## User types

### 1) Customer

A visitor who browses the menu, adds items to a cart, and places an order.

### 2) Restaurant Admin (Cafe Delight staff)

A staff member who logs in to add/edit food items, create offers, and manage incoming orders.

---

## Customer experience (Public website)

### Pages

* **Home**
  * Hero section (Cafe Delight branding + "Order Now" button)
  * Featured items
  * Active offers / deals
  * Quick categories (Pizza, Burgers, Drinks, Cakes, etc.)
  * Restaurant info (hours, location, contact)

* **Menu**
  * Category tabs/filters
  * Item cards with image, name, price, availability
  * Search (optional MVP)

* **Item Details**
  * Photos
  * Description
  * Options/add-ons (optional MVP)
  * Notes (e.g., "less spicy")
  * Add to cart

* **Offers**
  * List of active offers
  * Offer rules (what it applies to)

* **Cart**
  * Items, quantity controls, remove
  * Subtotal, discount, delivery fee (optional)
  * Checkout button

* **Checkout**
  * Customer name + phone
  * Address (if delivery)
  * Delivery or Pickup
  * Payment method: **Cash on Delivery** (MVP)
  * Place order

* **Order Confirmation + Tracking**
  * Order ID
  * Status timeline: `Received → Preparing → Ready → Out for delivery → Delivered / Cancelled`

### Customer features

* Browse menu by category
* Add/remove items from cart
* Adjust quantity
* Add item notes
* Place order without account (MVP)
* View order status using order ID + phone (simple tracking)

---

## Admin experience (Restaurant portal)

### Admin pages

* **Login**
* **Dashboard**
  * New orders count
  * Today's order summary (basic)

* **Menu Management**
  * Create/edit/delete categories
  * Create/edit/delete menu items
  * Upload images
  * Toggle availability (in stock/out of stock)
  * Mark featured items

* **Offers Management**
  * Create offers:
    * Percentage discount
    * Fixed amount discount
    * Buy 1 Get 1 (optional later)
  * Set start/end date
  * Choose scope:
    * entire menu
    * by category
    * by specific items

* **Orders Management**
  * Orders list with filters (new, preparing, delivered)
  * Order details view
  * Update status
  * Print-friendly order view (kitchen-friendly)

### Admin features

* Maintain menu daily without developer help
* Manage offers quickly (scheduled)
* Process orders with a clear status workflow
* Track customer details per order

---

## Data & business logic (core rules)

### Menu + Categories

* Menu items belong to a category
* Menu item has:
  * name, description, price
  * image
  * availability flag
  * featured flag
* Price changes should not affect old orders (store snapshots in order items)

### Offers

* Offer types:
  * Percentage (e.g., 10% off)
  * Fixed (e.g., ৳50 off)
* Offer applies to:
  * whole menu OR category OR selected items
* Offers only apply when:
  * active flag is true
  * current date within start/end

### Orders

* An order includes:
  * customer info (name, phone, address)
  * order type: delivery/pickup
  * status workflow
  * totals: subtotal, discount, final total
  * items (name snapshot, price snapshot, qty, notes)

---

## Non-functional requirements (quality)

* Mobile-first responsive UI (most restaurant users are mobile)
* Fast loading (optimized images, clean UI)
* Simple checkout (minimal fields)
* Admin easy to use (no clutter)
* Secure admin auth
* Basic validation everywhere

---

## MVP scope vs Phase 2

### MVP (must-have)

* Menu + categories
* Offers
* Cart + checkout (COD)
* Order management + status
* Admin panel for menu/offers/orders
* Order tracking by order ID

### Phase 2 (nice upgrades)

* Customer accounts + order history
* Online payments (Stripe/SSLCommerz)
* Delivery zones + delivery fee rules
* Add-ons/options (size, toppings, extras)
* Coupons
* SMS/WhatsApp order notifications
* Kitchen display mode
* Analytics (top selling, revenue stats)
* Multi-branch support

---

## Suggested tech direction (Next.js option)

Best MVP stack (alternative):

* **Next.js (web app)**
* **Supabase (database + admin auth + image storage)**
* **shadcn/ui + Tailwind (professional UI)**

This gives you: Fast development, easy admin auth, reliable database + storage, clean modern UI.

---

## Recommended stack (Laravel — best balance for MVP + scalable)

### Backend (core)

* **Laravel (latest stable)**
* **MySQL** (best if you'll deploy on cPanel/shared hosting)  
  *(If you're 100% on VPS, PostgreSQL is also fine — but MySQL is the safest default.)*

### Restaurant portal (Admin)

* **Filament**
  * Fastest way to build a professional admin panel
  * Great for: Categories, Menu Items, Offers, Orders, Status updates, Staff accounts

### Customer portal (Website)

* **Laravel Blade + Tailwind CSS + Alpine.js**
  * Blade = simple + SEO friendly
  * Tailwind = modern UI fast
  * Alpine = enough interactivity (cart drawer, modals, filters) without SPA complexity

### Auth + roles

* **Filament Auth** (for admin login)
* **spatie/laravel-permission**
  * Roles: `owner`, `staff` (optional), `viewer` (optional)

### Images (food photos)

* **Spatie Media Library**
* Storage:
  * MVP: **local storage** (`storage/app/public`)
  * Later: move to **S3 / DigitalOcean Spaces / Cloudflare R2** without changing your code much

### Ordering (MVP-simple but solid)

* **Guest checkout** (no customer account required)
* **Session-based cart**
* Order tracking via: `order_code + phone` (easy for customers)

### Optional but recommended (small effort, big value)

* **Laravel Pint** (formatting)
* **Laravel Backup (spatie/laravel-backup)** (daily backups)
* **Print-friendly order page** (HTML print). PDF can be phase 2.

---

## Portal structure (clean separation)

### Customer portal (public)

* Home: featured items + active offers
* Menu: category filters + add to cart
* Cart drawer/page
* Checkout (name, phone, address, delivery/pickup)
* Order tracking (status timeline)
* Contact/location

### Restaurant portal (Filament admin)

* Dashboard: new orders + today summary
* Menu management (CRUD + availability toggle + featured)
* Offer management (date range + scope)
* Orders list + detail view + status update + print
* Staff management (optional)

---

## Why this is the best setup

* **Fast to build** (Filament handles admin complexity)
* **Looks professional** (Tailwind + Blade pages)
* **SEO-friendly** (important for local restaurants)
* **Simple ordering** (no account needed)
* **Scales cleanly** (can add payments, delivery fees, WhatsApp, customer accounts later)

---

## Final recommendation (copy/paste)

✅ **Laravel + MySQL + Filament (Restaurant Portal) + Blade/Tailwind/Alpine (Customer Portal) + Spatie Permission + Spatie Media Library**

This is the best "production-quality MVP" path for Cafe Delight.

---

## Next steps (when ready)

* Full folder/module structure
* DB schema overview
* Route map for both portals
* Step-by-step build plan (Cursor prompts + commit messages)
