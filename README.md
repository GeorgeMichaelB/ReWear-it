# ReWear-it - Recycled Clothes Marketplace

A full-stack recycled clothes marketplace application built with Laravel (backend API), MySQL (database), and React + Tailwind (frontend).

## Quick Start (Windows with Docker)

### Prerequisites
- Windows 10/11 (Pro or Enterprise recommended)
- At least 4GB RAM available
- 10GB free disk space

### Installation Steps

1. **Install Docker Desktop**
   - Download from: https://www.docker.com/products/docker-desktop
   - Install and start Docker Desktop
   - Wait until Docker shows "Running" in the system tray

2. **Run the Project**
   ```powershell
   # Navigate to project directory
   cd C:\path\to\ReWear-it

   # Run the setup script (as Administrator)
   .\Setup-ReWear-it.ps1
   ```

   Or manually:
   ```powershell
   cd C:\path\to\ReWear-it
   docker-compose up -d
   ```

3. **Access the Application**
   - Open browser: http://localhost:3000

---

## Services & URLs

| Service     | URL                   | Description          |
|-------------|-----------------------|----------------------|
| Frontend    | http://localhost:3000 | React application    |
| Backend API | http://localhost:8000 | Laravel REST API     |
| MySQL       | localhost:3306        | Database             |

---

## Database Credentials

| Property    | Value          |
|-------------|----------------|
| Host        | mysql (Docker) |
| Port        | 3306           |
| Database    | rewearit       |
| Username    | rewearit       |
| Password    | rewearit123    |

---

## API Endpoints

### Public Routes (GET - No Auth Required)

```
GET  /api/message                  - Health check
GET  /api/categories               - List all categories
GET  /api/categories/{id}          - Get category by ID
GET  /api/items                    - List all items (with ?category_id=X filter)
GET  /api/items/{id}               - Get item details
GET  /api/items/{id}/carbon-savings - Get carbon savings for item
GET  /api/products                 - List all products
GET  /api/products/{id}            - Get product details
GET  /api/material-categories      - List material categories
GET  /api/style-boards             - List style boards
GET  /api/transactions             - List transactions
GET  /api/sale-orders              - List sale orders
GET  /api/disputes                 - List disputes
GET  /api/reviews                  - List reviews
GET  /api/transformation-logs      - List transformation logs
GET  /api/escrow-services          - List escrow services
GET  /api/swap-agreements          - List swap agreements
GET  /api/creators                 - List creators
```

### Authentication Routes

```
POST /api/auth/register            - Register new user
POST /api/auth/login               - Login (returns token)
POST /api/auth/logout              - Logout (requires auth)
GET  /api/auth/user                - Get current user (requires auth)
PUT  /api/auth/profile             - Update profile (requires auth)
PUT  /api/auth/password            - Change password (requires auth)
```

### Protected Routes (POST/PUT/DELETE - Auth Required)

```
# Items
GET  /api/items/my                 - Get my items
POST /api/items                    - Create new item
PUT  /api/items/{id}               - Update item
DELETE /api/items/{id}             - Delete item
PUT  /api/items/{id}/status        - Update item status

# Categories
POST /api/categories               - Create category
PUT  /api/categories/{id}          - Update category
DELETE /api/categories/{id}        - Delete category

# Orders
GET  /api/orders                   - List orders
GET  /api/orders/seller            - Seller's orders
GET  /api/orders/{id}              - Get order details
POST /api/orders                   - Create order
PUT  /api/orders/{id}/status       - Update order status

# Favorites
GET  /api/favorites                - List favorites
POST /api/favorites                - Add to favorites
DELETE /api/favorites/{id}         - Remove from favorites

# Addresses
GET  /api/addresses                - List addresses
POST /api/addresses                - Create address
PUT  /api/addresses/{id}           - Update address
DELETE /api/addresses/{id}        - Delete address

# Style Boards
GET  /api/style-boards/my          - My style boards
POST /api/style-boards             - Create style board
PUT  /api/style-boards/{id}        - Update style board
DELETE /api/style-boards/{id}     - Delete style board
POST /api/style-boards/{id}/items  - Add item to board
DELETE /api/style-boards/{id}/items/{itemId} - Remove item from board

# Transactions
POST /api/transactions             - Create transaction
POST /api/transactions/{id}/cancel - Cancel transaction
POST /api/transactions/{id}/complete - Complete transaction

# And more...
```

---

## Test Accounts

| Role    | Email                    | Password     |
|---------|--------------------------|--------------|
| Admin   | admin@rewearit.com       | password123  |
| Seller  | seller@rewearit.com      | password123  |
| Seller  | seller2@rewearit.com     | password123  |
| Buyer   | buyer@rewearit.com       | password123  |

---

## Frontend Routes

| Path       | Description                    |
|------------|--------------------------------|
| /          | Home page                      |
| /products  | Browse items listing           |
| /login     | Login page                     |
| /register  | Registration page              |
| /account   | User account (profile edit)    |

---

## Development (Manual Setup without Docker)

### Backend (Laravel)
```bash
cd backend-api

# Install dependencies
composer install

# Setup environment
cp .env.example .env
# Edit .env with your MySQL credentials (port 3307 if using Docker)

# Generate key and migrate
php artisan key:generate
php artisan migrate:fresh --seed

# Start server
php artisan serve
```

### Frontend (React)
```bash
cd my-project

# Install dependencies
npm install

# Start development server
npm start
```

### Database (Docker MySQL)
```bash
docker run -d --name rewearit-mysql \
  -e MYSQL_ROOT_PASSWORD=rootpassword \
  -e MYSQL_DATABASE=rewearit \
  -e MYSQL_USER=rewearit \
  -e MYSQL_PASSWORD=rewearit123 \
  -p 3307:3306 \
  mysql:8.0
```

---

## Docker Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# View status
docker-compose ps

# Rebuild containers
docker-compose build --no-cache
```

---

## Tech Stack

- **Backend:** Laravel 10, PHP 8.2
- **Database:** MySQL 8.0
- **Frontend:** React 19, Tailwind CSS
- **Authentication:** Laravel Sanctum
- **Container:** Docker, Docker Compose