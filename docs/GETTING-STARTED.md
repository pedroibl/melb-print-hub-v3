# 🚀 **Getting Started Guide - Melbourne Print Hub Laravel Project**

## 📋 **Prerequisites**
- macOS with Homebrew installed
- PHP 8.1+ 
- Composer
- Node.js & npm
- Git

---

## 🏗️ **Project Setup**

### **1. Clone the Repository**
```bash
git clone https://github.com/pedroibl/melb-print-hub-lara.git
cd melb-print-hub-lara
```

### **2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### **3. Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### **4. Configure .env File**
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=melb_print_hub
DB_USERNAME=melb_print_user
DB_PASSWORD=your_database_password_here
MYSQL_ROOT_PASSWORD=your_root_password_here
```

> **⚠️ Security Note**: The `.env` file is automatically ignored by Git and will not be tracked. Never commit real passwords to version control.

---

## 🗄️ **Database Setup**

### **1. Install MySQL via Homebrew**
```bash
# Install MySQL
brew install mysql

# Start MySQL service
brew services start mysql
```

### **2. Create Database and User**
```bash
# Access MySQL as root (no password initially)
mysql -u root

# Create database and user
CREATE DATABASE melb_print_hub;
CREATE USER 'melb_print_user'@'localhost' IDENTIFIED BY 'your_database_password_here';
GRANT ALL PRIVILEGES ON melb_print_hub.* TO 'melb_print_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### **3. Configure MySQL Client (Optional - Password-less Access)**
```bash
# Create MySQL client configuration
cat > ~/.my.cnf << 'EOF'
[client]
user=melb_print_user
password=your_database_password_here
host=127.0.0.1
port=3306

[mysql]
user=melb_print_user
password=your_database_password_here
host=127.0.0.1
port=3306
database=melb_print_hub

[mysqladmin]
user=root
password=your_root_password_here
host=127.0.0.1
EOF
```

> **⚠️ Security Note**: The `~/.my.cnf` file contains passwords and should not be shared or committed to version control. Keep it secure on your local machine only.

### **4. Run Database Migrations**
```bash
# Run migrations to create tables
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

---

## 🖥️ **phpMyAdmin Setup**

### **1. Install phpMyAdmin**
```bash
# Install via Homebrew
brew install phpmyadmin
```

### **2. Create Symbolic Link**
```bash
# Create symbolic link in Laravel public directory
ln -s /opt/homebrew/share/phpmyadmin public/phpmyadmin
```

### **3. Access phpMyAdmin**
- **URL**: `http://127.0.0.1:8000/phpmyadmin`
- **Username**: `melb_print_user`
- **Password**: `your_database_password_here`
- **Server**: `127.0.0.1`

---

## 🚀 **Starting the Application**

### **1. Start Laravel Development Server**
```bash
# Start the server
php artisan serve --host=127.0.0.1 --port=8000

# Or run in background
php artisan serve --host=127.0.0.1 --port=8000 &
```

### **2. Build Frontend Assets**
```bash
# Development mode (with hot reload)
npm run dev

# Production build
npm run build
```

### **3. Access the Application**
- **Main Site**: `http://127.0.0.1:8000`
- **Services**: `http://127.0.0.1:8000/services`
- **Get Quote**: `http://127.0.0.1:8000/get-quote`
- **Contact**: `http://127.0.0.1:8000/contact`
- **phpMyAdmin**: `http://127.0.0.1:8000/phpmyadmin`

---

## 🛠️ **Development Workflow**

### **1. Database Management**
```bash
# Access MySQL directly
mysql -u melb_print_user melb_print_hub

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh --seed
```

### **2. Laravel Commands**
```bash
# Clear caches
php artisan config:clear
php artisan view:clear
php artisan route:clear

# List routes
php artisan route:list

# Access Tinker (interactive shell)
php artisan tinker
```

### **3. Frontend Development**
```bash
# Watch for changes
npm run dev

# Build for production
npm run build
```

---

## 🔧 **Troubleshooting**

### **Common Issues & Solutions**

#### **1. MySQL Connection Issues**
```bash
# Check MySQL status
brew services list | grep mysql

# Restart MySQL
brew services restart mysql

# Check MySQL logs
tail -f /opt/homebrew/var/log/mysql.log
```

#### **2. Port Already in Use**
```bash
# Find process using port 8000
lsof -i :8000

# Kill process
kill -9 <PID>
```

#### **3. Database Migration Errors**
```bash
# Reset database completely
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

#### **4. Frontend Assets Not Loading**
```bash
# Clear Laravel caches
php artisan config:clear
php artisan view:clear

# Rebuild frontend assets
npm run build
```

---

## 📱 **Quick Start Commands**

### **One-Line Startup**
```bash
# Start everything in one command
brew services start mysql && php artisan serve --host=127.0.0.1 --port=8000 &
```

### **Development Mode**
```bash
# Terminal 1: Start Laravel
php artisan serve --host=127.0.0.1 --port=8000

# Terminal 2: Watch frontend changes
npm run dev

# Terminal 3: Monitor database
mysql -u melb_print_user melb_print_hub
```

---

## 🌐 **Access Points Summary**

| Service | URL | Credentials |
|---------|-----|-------------|
| **Laravel App** | `http://127.0.0.1:8000` | None |
| **phpMyAdmin** | `http://127.0.0.1:8000/phpmyadmin` | `melb_print_user` / `your_database_password_here` |
| **MySQL CLI** | `mysql -u melb_print_user` | `your_database_password_here` |
| **Admin Panel** | `http://127.0.0.1:8000/admin` | Register/Login required |

---

## 📚 **Additional Resources**

- **Laravel Documentation**: https://laravel.com/docs
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **phpMyAdmin Documentation**: https://docs.phpmyadmin.net/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Inertia.js**: https://inertiajs.com/

---

## 🆘 **Need Help?**

If you encounter issues:
1. Check the troubleshooting section above
2. Verify all services are running
3. Check the Laravel logs: `tail -f storage/logs/laravel.log`
4. Ensure all dependencies are installed
5. Verify database credentials in `.env`

---

**Happy Coding! 🎉**
