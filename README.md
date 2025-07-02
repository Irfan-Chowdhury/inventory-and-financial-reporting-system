# 📦 Inventory & Financial Reporting System

A Laravel-based simple Inventory Management System integrated with Accounting Journals and Financial Reporting. This application helps small businesses track product stock, sales, and generate financial reports with VAT, discount, and due calculations.

---

## 📚 Project Overview

This system was built as part of an assessment to demonstrate understanding of inventory handling, sales processing, accounting integration and report generation.

---

## 📁 Demo Project


- **Demo:** [http://ecommerce.irfandev.xyz/login](http://ecommerce.irfandev.xyz/login)
<br>
Credentials :

    ```php
    email: admin@gmail.com
    password: admin@gmail.com
    ```
---

## ✅ Key Features

### 📦 Inventory Module
- Product Create/List with:
  - Product Name, Purchase Price, Sell Price, Opening Stock
- Real-time Stock Management:
  - Stock reduced on sale
  - Remaining quantity updated automatically

---

### 💰 Sales Module
- Create sale with:
  - Discount input
  - 5% VAT auto-calculated (after discount)
  - Paid amount input
- Calculates:
  - **Total Payable** = `(sell_price * quantity) - discount + VAT`
  - **Due** = `Total Payable - Paid`
- Stock automatically reduced after each sale

---

### 📊 Financial Report
- Filterable by custom date range
- Displays:
  - ✅ **Total Sales**
  - ✅ **Total Discount**
  - ✅ **Total VAT**
  - ✅ **Total Expenses** (based on purchase price × sold quantity)
  - ✅ **Net Profit** = `Sales - Expenses - Discount`
- Option for:
  - **Summary Report**
  - **Detailed Report** (sale-wise breakdown)

---

### 📘 Accounting Journals
Auto-managed journal breakdown per sale:
- Sales Revenue
- Discount Given
- VAT Collected
- Payment Received (Cash)
- Remaining Due

---

## 📦 Sample Data (for testing)

### Initial Inventory:
- Product: Example Item
- Purchase Price: 100 TK
- Sell Price: 200 TK
- Opening Stock: 50 units

### Sample Sale:
- Sold Quantity: 10 units
- Discount: 50 TK
- VAT: 5% (on discounted amount)
- Paid: 1000 TK


---

## ⚙️ Installation Guide

### Prerequisites:
- PHP 8.2+
- Composer
- MySQL
- Node.js & npm (for frontend build if used)

---

### Setup Steps

#### 1. Clone the repository :

```bash
git clone https://github.com/your-username/inventory-finance-system.git
cd inventory-finance-system
```

#### 2. Install PHP dependencies

```bash
composer install
```

#### 3. Copy .env file and configure

```bash
cp .env.example .env
```

#### 4. Set database, app_url, etc.

```bash
APP_URL=your_root_url
SSO_REDIRECT_URL=your_redirect_url

DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# If your domain with https:// then comment out and set with proper data
# If local machine, then comment the 3 lines
SESSION_DOMAIN=.irfandev.xyz
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

#### 5. Generate app key & run migrations

```bash
php artisan key:generate
php artisan migrate --seed
```


#### 6. (Optional) Install frontend dependencies

```bash
npm install && npm run build

```

#### 7. Serve the application
```bash
php artisan serve
```

<br>

## ✅ Test Case Screenshot

This is a sample test result from running PEST tests:

#### Product Test :

run command : 
```bash
./vendor/bin/pest tests/Feature/ProductTest.php
```

![Test Case Screenshot](https://snipboard.io/6KcY9w.jpg)


#### Sale Test :

run command : 
```bash
./vendor/bin/pest tests/Feature/SaleTest.php
```

![Test Case Screenshot](https://snipboard.io/yluNnp.jpg)

#### Report Test :

run command : 
```bash
./vendor/bin/pest tests/Feature/ReportTest.php
```

![Test Case Screenshot](https://snipboard.io/zaHcEI.jpg)


<br>

## 👨‍💻 Author

**Md Irfan Chowdhury** <br>
PHP-Laravel Developer  <br>
🔗 [LinkedIn Profile](https://www.linkedin.com/in/irfan-chowdhury/) | 📧 [irfanchowdhury80@gmail.com](irfanchowdhury80@gmail.com)
