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
- PHP 8.x+
- Composer
- Laravel 10/11
- MySQL or MariaDB
- Node.js & npm (for frontend build if used)

---

### Setup Steps

```bash
# 1. Clone the repository
git clone https://github.com/your-username/inventory-finance-system.git
cd inventory-finance-system

# 2. Install PHP dependencies
composer install

# 3. Copy .env file and configure
cp .env.example .env
# ➤ Set database, app_url, etc.

# 4. Generate app key & run migrations
php artisan key:generate
php artisan migrate --seed

# 5. (Optional) Install frontend dependencies
npm install && npm run build

# 6. Serve the application
php artisan serve
```

## 👨‍💻 Author

**Md Irfan Chowdhury** <br>
PHP-Laravel Developer  <br>
🔗 [LinkedIn Profile](https://www.linkedin.com/in/irfan-chowdhury/) | 📧 [irfanchowdhury80@gmail.com](irfanchowdhury80@gmail.com)
