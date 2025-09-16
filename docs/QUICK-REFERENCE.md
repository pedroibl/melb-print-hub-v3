# ⚡ **Quick Reference Card - Melbourne Print Hub**

## 🚀 **Daily Startup Commands**

### **Start Everything**
```bash
# Start MySQL
brew services start mysql

# Start Laravel (in background)
php artisan serve --host=127.0.0.1 --port=8000 &

# Build frontend assets
npm run build
```

### **Access Points**
- **Website**: http://127.0.0.1:8000
- **phpMyAdmin**: http://127.0.0.1:8000/phpmyadmin
- **Database**: `mysql -u melb_print_user melb_print_hub`

---

## 🛠️ **Common Commands**

### **Laravel**
```bash
# Start server
php artisan serve --host=127.0.0.1 --port=8000

# Clear caches
php artisan config:clear && php artisan view:clear

# Run migrations
php artisan migrate

# Reset database
php artisan migrate:fresh --seed

# Access Tinker
php artisan tinker
```

### **Frontend**
```bash
# Development mode
npm run dev

# Production build
npm run build

# Install dependencies
npm install
```

### **Database**
```bash
# Access MySQL
mysql -u melb_print_user melb_print_hub

# Check MySQL status
brew services list | grep mysql

# Restart MySQL
brew services restart mysql
```

---

## 🔧 **Troubleshooting Quick Fixes**

### **Port 8000 Busy**
```bash
lsof -i :8000
kill -9 <PID>
```

### **Assets Not Loading**
```bash
npm run build
php artisan config:clear
```

### **Database Issues**
```bash
brew services restart mysql
php artisan migrate:fresh --seed
```

---

## 📱 **Development Workflow**

### **Terminal 1: Backend**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### **Terminal 2: Frontend**
```bash
npm run dev
```

### **Terminal 3: Database**
```bash
mysql -u melb_print_user melb_print_hub
```

---

## 🌐 **Quick URLs**

| Page | URL |
|------|-----|
| Home | `/` |
| Services | `/services` |
| Get Quote | `/get-quote` |
| Contact | `/contact` |
| Admin | `/admin` |
| phpMyAdmin | `/phpmyadmin` |

---

**Credentials**: `melb_print_user` / `your_database_password_here`

> **⚠️ Security Note**: Replace `your_database_password_here` with your actual database password. Never commit real passwords to version control.
