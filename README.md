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

---

## Use Cases Implementation (UC-1 to UC-42)

This section documents all 42 use cases and their implementation locations.

### UC-1 to UC-10: Core Features & User Management

| UC | Description | Controller | Frontend Page |
|----|-------------|------------|---------------|
| UC-1 | Pro-Upcycler Badges (seller verification) | `SellerBadgeController.php` | Trust Score page |
| UC-2 | CO2 & Water Savings Calculation | `TransformationController.php` | Eco Impact page |
| UC-3 | User Roles (buyer/seller/admin) | `AuthController.php` | Account page |
| UC-4 | Advanced Marketplace Filters | `CategoryAdvancedController.php` | Products page |
| UC-5 | Eco-Credits System | `AuthController.php` | Eco Credits page |
| UC-6 | Trust Score Algorithm | `SellerBadgeController.php` | Trust Score page |
| UC-7 | Digital Closet (personal wardrobe) | `DigitalClosetController.php` | Digital Closet page |
| UC-8 | Before/After Transformations | `TransformationController.php` | Eco Impact page |
| UC-9 | Material Taxonomy | `MaterialCategoryController.php` | Products page |
| UC-10 | Item Locking (sale/swap exclusivity) | `ItemLockController.php` | My Items page |

### UC-11 to UC-20: Advanced Trading Features

| UC | Description | Controller | Frontend Page |
|----|-------------|------------|---------------|
| UC-11 | Item Lock for Transaction | `ItemLockController.php` | My Items page |
| UC-12 | Prohibited Content Validation | `ItemLockController.php` | My Items page |
| UC-13 | Bulk Listings | `BulkListingController.php` | My Items page |
| UC-14 | Care Instructions Generation | `CareInstructionController.php` | My Items page |
| UC-15 | Multi-Item Swap Proposal | `SwapBundleController.php` | Swap Proposal page |
| UC-16 | Cash Top-Up Calculator | `SwapBundleController.php` | Swap Proposal page |
| UC-17 | Bargaining Thresholds | `SwapBundleController.php` | Swap Proposal page |
| UC-18 | Auto-Cancel Expired Offers | `ItemLockController.php` | Swap History page |
| UC-19 | Lock Item After Agreement Signed | `ItemLockController.php` | Swap History page |
| UC-20 | Category Advanced Features | `CategoryAdvancedController.php` | Products page |

### UC-21 to UC-30: Location, Payments & Social

| UC | Description | Controller | Frontend Page |
|----|-------------|------------|---------------|
| UC-21 | Geospatial - Find Nearby Users | `GeospatialController.php` | Nearby Swaps page |
| UC-22 | Escrow Payment (virtual vault) | `EscrowController.php` | Escrow Vault page |
| UC-23 | Dynamic Platform Fees | `EscrowController.php` | Escrow Vault page |
| UC-24 | Shipping Label Generation | `ShippingController.php` | Shipping page |
| UC-25 | Reverse Logistics (returns) | `ShippingController.php` | Shipping page |
| UC-26 | Bundle Discounts | `ShippingController.php` | Bundle Discount page |
| UC-27 | Payout Scheduling | `EscrowController.php` | Escrow Vault page |
| UC-28 | Currency Conversion | `EscrowController.php` | Escrow Vault page |
| UC-29 | Dispute Mediation Hub | `DisputeController.php` | Dispute Center page |
| UC-30 | Collaborative Style Boards | `StyleBoardController.php` | Style Boards page |

### UC-31 to UC-40: Notifications, Analytics & Admin

| UC | Description | Controller | Frontend Page |
|----|-------------|------------|---------------|
| UC-31 | Live Drop Notifications | `NotificationController.php` | Drops page |
| UC-32 | Seller Performance Analytics | `AnalyticsController.php` | Seller Analytics page |
| UC-33 | Nested Comment Threads | `CommentController.php` | Comments page |
| UC-34 | Multi-Stage Reporting + Shadow-Ban | `ReportController.php` | Reports page |
| UC-35 | Mentor-Mentee Pairing | `MentorshipController.php` | Mentorship page |
| UC-36 | Market Trends & Materials | `MarketTrendsController.php` | Market Trends page |
| UC-37 | Dynamic Commission Modifiers | `AdminController.php` | Admin Dashboard |
| UC-38 | Sustainability Audit Report | `AdminController.php` | Admin Dashboard |
| UC-39 | Role-Based Access Control | `AdminController.php` | Admin Dashboard |
| UC-40 | System Health Monitoring | `AnalyticsController.php` | Admin Dashboard |

### UC-41 to UC-42: Automation & Maintenance

| UC | Description | Controller | Frontend Page |
|----|-------------|------------|---------------|
| UC-41 | Weekly Newsletter Curation | `NewsletterController.php` | Newsletter page |
| UC-42 | Database Cleanup & Archiving | `DatabaseCleanupController.php` | Cleanup page |

---

## API Structure by Feature

### Controllers Location
All backend controllers are in: `backend-api/app/Http/Controllers/`

### Frontend Pages Location
All frontend pages are in: `my-project/src/pages/`

### Frontend Services
API client is in: `my-project/src/services/api.js`

---

## Testing the Use Cases

1. Start the backend: `cd backend-api && php artisan serve`
2. Start the frontend: `cd my-project && npm run dev`
3. Login with test account (buyer@rewearit.com / password123)
4. Navigate to the corresponding page for each use case

### Quick Reference: Frontend Routes

| Page | Route | Use Cases |
|------|-------|-----------|
| Eco Credits | /eco-credits | UC-2, UC-5 |
| Trust Score | /trust-score | UC-1, UC-6 |
| Digital Closet | /digital-closet | UC-7 |
| Eco Impact | /eco-impact | UC-2, UC-8 |
| Swap Proposal | /swap-proposal | UC-15, UC-16, UC-17 |
| Nearby Swaps | /nearby-swaps | UC-21 |
| Escrow Vault | /escrow-vault | UC-22, UC-23, UC-27, UC-28 |
| Dispute Center | /dispute-center | UC-29 |
| Style Boards | /style-boards | UC-30 |
| Drops | /drops | UC-31 |
| Seller Analytics | /seller-analytics | UC-32 |
| Comments | /comments | UC-33 |
| Reports | /reports | UC-34 |
| Mentorship | /mentorship | UC-35 |
| Market Trends | /trends | UC-36 |
| Admin Dashboard | /admin | UC-37, UC-38, UC-39, UC-40 |
| Newsletter | /newsletter | UC-41 |
| Cleanup | /cleanup | UC-42 |

---

## Testing

### Backend Tests (Laravel)

All backend tests are located in `backend-api/tests/Feature/`

#### Running Backend Tests

```bash
cd backend-api

# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=SellerBadgeApiTest

# Run with coverage
php artisan test --coverage
```

#### Test Files by Feature

| Test File | Coverage |
|-----------|----------|
| `AuthApiTest.php` | UC-3, UC-5 (auth, eco-credits) |
| `SellerBadgeApiTest.php` | UC-1, UC-6 (badges, trust score) |
| `CategoryApiTest.php` | UC-4, UC-20 (categories) |
| `ItemApiTest.php` | Basic CRUD operations |
| `DigitalClosetApiTest.php` | UC-7 (digital closet) |
| `SwapBundleApiTest.php` | UC-15, UC-16, UC-17 (swap bundles) |
| `GeospatialApiTest.php` | UC-21 (nearby users) |
| `EscrowApiTest.php` | UC-22, UC-23, UC-27, UC-28 |
| `ShippingApiTest.php` | UC-24, UC-25, UC-26 |
| `DisputeApiExtendedTest.php` | UC-29 |
| `StyleBoardApiExtendedTest.php` | UC-30 |
| `NotificationApiTest.php` | UC-31 (drop notifications) |
| `AnalyticsApiTest.php` | UC-32, UC-40 |
| `CommentApiTest.php` | UC-33 (nested comments) |
| `ReportApiTest.php` | UC-34 (reporting, shadow-bans) |
| `MentorshipApiTest.php` | UC-35 |
| `MarketTrendsApiTest.php` | UC-36 |
| `AdminApiTest.php` | UC-37, UC-38, UC-39 |
| `NewsletterApiTest.php` | UC-41 |
| `DatabaseCleanupApiTest.php` | UC-42 |

### Frontend Tests (React/Vitest)

All frontend tests are located in `my-project/src/__tests__/`

#### Running Frontend Tests

```bash
cd my-project

# Install test dependencies first
npm install

# Run all tests
npm test

# Run with UI
npm run test:ui

# Run with coverage
npm run test:coverage
```

#### Test Files by Feature

| Test File | Coverage |
|-----------|----------|
| `AuthPages.test.jsx` | Login, Register, Home pages |
| `CorePages.test.jsx` | MyItems (UC-10-13), SwapProposal (UC-15-17), DigitalCloset (UC-7), EcoImpact (UC-2,8) |
| `LocationPaymentPages.test.jsx` | NearbySwaps (UC-21), EscrowVault (UC-22-28), DisputeCenter (UC-29), StyleBoards (UC-30) |
| `AnalyticsPages.test.jsx` | DropNotifications (UC-31), SellerAnalytics (UC-32), Comments (UC-33), Reports (UC-34), Mentorship (UC-35), Trends (UC-36), Admin (UC-37-40) |
| `AutomationPages.test.jsx` | Newsletter (UC-41), DatabaseCleanup (UC-42), API services |

### Test Coverage Summary

- **UC-1 to UC-10**: SellerBadgeApiTest, DigitalClosetApiTest, AuthApiTest, CategoryApiTest
- **UC-11 to UC-20**: SwapBundleApiTest, CareInstructionApiTest, BulkListingApiTest, CategoryAdvancedApiTest
- **UC-21 to UC-30**: GeospatialApiTest, EscrowApiTest, ShippingApiTest, DisputeApiExtendedTest, StyleBoardApiExtendedTest
- **UC-31 to UC-40**: NotificationApiTest, AnalyticsApiTest, CommentApiTest, ReportApiTest, MentorshipApiTest, MarketTrendsApiTest, AdminApiTest
- **UC-41 to UC-42**: NewsletterApiTest, DatabaseCleanupApiTest

### Writing New Tests

#### Backend (Laravel)
```php
// In tests/Feature/YourTest.php
public function test_your_feature()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/your-endpoint', [
            'param' => 'value',
        ]);
    
    $response->assertStatus(200)
        ->assertJsonStructure(['expected', 'structure']);
}
```

#### Frontend (React)
```jsx
// In src/__tests__/YourTest.test.jsx
import { render, screen } from '@testing-library/react';

test('renders your component', () => {
  render(<YourComponent />);
  expect(screen.getByText(/expected/i)).toBeInTheDocument();
});
```