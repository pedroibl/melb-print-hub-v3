# 🚀 **Heroku Deployment Guide - Laravel + MySQL**

## 📋 **Heroku Overview**

**Cost**: $12/month total
- **Laravel App**: $7/month (Basic dyno)
- **MySQL Database**: $5/month (Basic addon)
- **Total**: $12/month

**Why Heroku:**
- ✅ **Very reliable** - Used by many large companies
- ✅ **Great documentation** - Extensive Laravel guides
- ✅ **Easy scaling** - Can upgrade as needed
- ✅ **Add-ons ecosystem** - Many database options
- ✅ **Git-based deployments** - Automatic from GitHub

---

## 🚀 **Step-by-Step Heroku Setup**

### **Step 1: Create Heroku Account**
1. **Go to**: https://heroku.com
2. **Sign up** with email
3. **Verify email**
4. **Add payment method** (required for paid plans)

### **Step 2: Install Heroku CLI**
```bash
# macOS (using Homebrew)
brew tap heroku/brew && brew install heroku

# Or download from: https://devcenter.heroku.com/articles/heroku-cli
```

### **Step 3: Login to Heroku**
```bash
heroku login
```

### **Step 4: Create Heroku App**
```bash
# Navigate to your Laravel project
cd /Users/pibl/Sites/laravel-project

# Create Heroku app
heroku create melbourneprinthub

# This will give you a URL like: https://melbourneprinthub-12345.herokuapp.com
```

### **Step 5: Add MySQL Database**
```bash
# Add MySQL addon (JawsDB MySQL)
heroku addons:create jawsdb:kitefin

# This will add MySQL database and set DATABASE_URL environment variable
```

### **Step 6: Configure Environment Variables**
```bash
# Set Laravel environment variables
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://melbourneprinthub.com.au
heroku config:set APP_KEY=base64:1Yj1bX1t71znIBgjUDHlxpYHnySnB6rIG/Jx62udswc=
heroku config:set CACHE_DRIVER=file
heroku config:set SESSION_DRIVER=file
heroku config:set QUEUE_CONNECTION=sync
```

### **Step 7: Update Database Configuration**
```bash
# Get database URL
heroku config:get DATABASE_URL

# This will show something like: mysql://username:password@host:port/database
```

### **Step 8: Update Laravel Database Config**
Edit `config/database.php` to use Heroku's DATABASE_URL:

```php
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```

### **Step 9: Create Procfile**
Create `Procfile` in your project root:
```
web: vendor/bin/heroku-php-apache2 public/
```

### **Step 10: Update composer.json**
Add this to your `composer.json`:
```json
{
    "require": {
        "ext-mysql": "*"
    }
}
```

### **Step 11: Deploy to Heroku**
```bash
# Add all files to git
git add .

# Commit changes
git commit -m "Configure for Heroku deployment"

# Push to Heroku
git push heroku main

# Run migrations
heroku run php artisan migrate --force

# Seed database
heroku run php artisan db:seed --force
```

### **Step 12: Set Custom Domain**
```bash
# Add your custom domain
heroku domains:add melbourneprinthub.com.au
heroku domains:add www.melbourneprinthub.com.au

# This will give you DNS targets to configure in GoDaddy
```

---

## 🔧 **Heroku-Specific Configuration**

### **Update .env for Heroku**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://melbourneprinthub.com.au
APP_KEY=base64:1Yj1bX1t71znIBgjUDHlxpYHnySnB6rIG/Jx62udswc=
DB_CONNECTION=mysql
DB_URL=${DATABASE_URL}
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### **Create .env.production**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://melbourneprinthub.com.au
APP_KEY=base64:1Yj1bX1t71znIBgjUDHlxpYHnySnB6rIG/Jx62udswc=
DB_CONNECTION=mysql
DB_URL=${DATABASE_URL}
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 🗄️ **Database Migration**

### **Run Migrations**
```bash
# Run migrations on Heroku
heroku run php artisan migrate --force

# Seed database
heroku run php artisan db:seed --force

# Check database status
heroku run php artisan migrate:status
```

### **Database Backup**
```bash
# Create database backup
heroku pg:backups:capture

# Download backup
heroku pg:backups:download
```

---

## 🌐 **Domain Configuration**

### **Step 1: Get DNS Targets**
```bash
# Get DNS targets from Heroku
heroku domains

# This will show something like:
# melbourneprinthub.com.au -> melbourneprinthub-12345.herokuapp.com
# www.melbourneprinthub.com.au -> melbourneprinthub-12345.herokuapp.com
```

### **Step 2: Configure GoDaddy DNS**
1. **Log into GoDaddy**
2. **Go to Domain Management**
3. **Click "DNS" for melbourneprinthub.com.au**
4. **Add CNAME records**:
   ```
   Type: CNAME
   Name: @
   Value: melbourneprinthub-12345.herokuapp.com
   TTL: 600
   
   Type: CNAME
   Name: www
   Value: melbourneprinthub-12345.herokuapp.com
   TTL: 600
   ```

### **Step 3: SSL Certificate**
```bash
# Heroku provides automatic SSL
# No additional setup needed
```

---

## 📊 **Monitoring & Logs**

### **View Logs**
```bash
# View real-time logs
heroku logs --tail

# View recent logs
heroku logs

# View specific number of lines
heroku logs -n 200
```

### **Monitor Performance**
```bash
# Check app status
heroku ps

# Check dyno usage
heroku ps:scale

# Monitor database
heroku addons:open jawsdb
```

---

## 🔍 **Troubleshooting**

### **Common Issues:**

#### **Build Fails**
```bash
# Check build logs
heroku logs --tail

# Common fixes:
# 1. Ensure Procfile exists
# 2. Check composer.json
# 3. Verify PHP version
```

#### **Database Connection Issues**
```bash
# Check database URL
heroku config:get DATABASE_URL

# Test database connection
heroku run php artisan tinker
# Then try: DB::connection()->getPdo();
```

#### **Migration Fails**
```bash
# Run migrations manually
heroku run php artisan migrate --force

# Check migration status
heroku run php artisan migrate:status
```

---

## 💰 **Cost Breakdown**

### **Monthly Costs:**
- **Basic Dyno**: $7/month
- **JawsDB MySQL**: $5/month
- **Total**: $12/month

### **Scaling Options:**
- **Standard Dyno**: $25/month (better performance)
- **Premium Dyno**: $250/month (high performance)
- **JawsDB MySQL Premium**: $200/month (larger database)

---

## 🎯 **Success Checklist**

### **Pre-Deployment:**
- [ ] Heroku account created
- [ ] Heroku CLI installed
- [ ] Payment method added
- [ ] Laravel app configured

### **Deployment:**
- [ ] Heroku app created
- [ ] MySQL addon added
- [ ] Environment variables set
- [ ] Procfile created
- [ ] App deployed successfully

### **Post-Deployment:**
- [ ] Migrations run
- [ ] Database seeded
- [ ] Custom domain configured
- [ ] SSL certificate active
- [ ] App accessible at https://melbourneprinthub.com.au

---

## 🔗 **Useful Commands**

```bash
# Deploy updates
git push heroku main

# Run commands on Heroku
heroku run php artisan migrate
heroku run php artisan db:seed
heroku run php artisan cache:clear

# Check app status
heroku ps
heroku logs --tail

# Scale dynos
heroku ps:scale web=1

# Open app in browser
heroku open
```

---

**Status**: **Ready for Heroku Deployment** 🚀  
**Cost**: **$12/month** 💰  
**Setup Time**: **15-20 minutes** ⏱️  
**Next Step**: **Create Heroku Account** 📝
