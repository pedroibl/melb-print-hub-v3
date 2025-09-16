# 🗄️ **MySQL Database Setup for Vercel Deployment**

## 📋 **Overview**
Since Vercel is serverless, you need an external MySQL database. Here are the best options:

---

## 🚀 **Option 1: PlanetScale (Recommended)**

### **Why PlanetScale?**
- ✅ **Serverless MySQL** - Perfect for Vercel
- ✅ **Free tier** available
- ✅ **Automatic scaling**
- ✅ **Built-in connection pooling**
- ✅ **Easy setup**

### **Setup Steps:**

#### **Step 1: Create PlanetScale Account**
1. Go to: https://planetscale.com
2. **Sign up** with GitHub
3. **Verify email**

#### **Step 2: Create Database**
1. **Click "New Database"**
2. **Name**: `melb-print-hub`
3. **Region**: Choose closest to Australia
4. **Click "Create Database"**

#### **Step 3: Get Connection Details**
1. **Go to "Connect" tab**
2. **Select "Connect with Prisma"**
3. **Copy the connection string**

#### **Step 4: Configure Vercel Environment Variables**
```
DB_CONNECTION=mysql
DB_HOST=aws.connect.psdb.cloud
DB_PORT=3306
DB_DATABASE=melb-print-hub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 🚂 **Option 2: Railway**

### **Setup Steps:**

#### **Step 1: Create Railway Account**
1. Go to: https://railway.app
2. **Sign up** with GitHub
3. **Verify email**

#### **Step 2: Create MySQL Database**
1. **Click "New Project"**
2. **Select "Provision MySQL"**
3. **Wait for database to be created**

#### **Step 3: Get Connection Details**
1. **Click on MySQL service**
2. **Go to "Connect" tab**
3. **Copy connection details**

#### **Step 4: Configure Vercel Environment Variables**
```
DB_CONNECTION=mysql
DB_HOST=containers-us-west-XX.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## ☁️ **Option 3: Clever Cloud**

### **Setup Steps:**

#### **Step 1: Create Clever Cloud Account**
1. Go to: https://clever-cloud.com
2. **Sign up**
3. **Verify email**

#### **Step 2: Create MySQL Database**
1. **Click "Add a service"**
2. **Select "MySQL"**
3. **Choose plan and region**

#### **Step 3: Get Connection Details**
1. **Go to "Information" tab**
2. **Copy connection details**

#### **Step 4: Configure Vercel Environment Variables**
```
DB_CONNECTION=mysql
DB_HOST=your_host.clever-cloud.com
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 🗄️ **Database Migration Setup**

### **Step 1: Update Vercel Build Command**
In Vercel project settings, update the build command:
```bash
composer install --no-dev && npm install && npm run build && php artisan migrate --force
```

### **Step 2: Add Migration Script**
Create `vercel-build.sh`:
```bash
#!/bin/bash
composer install --no-dev
npm install
npm run build
php artisan migrate --force
```

### **Step 3: Update vercel.json**
```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "/public/index.php"
    }
  ],
  "env": {
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "APP_URL": "https://melbourneprinthub.com.au",
    "DB_CONNECTION": "mysql",
    "DB_HOST": "your_mysql_host_here",
    "DB_PORT": "3306",
    "DB_DATABASE": "your_database_name_here",
    "DB_USERNAME": "your_database_user_here",
    "DB_PASSWORD": "your_database_password_here",
    "CACHE_DRIVER": "file",
    "SESSION_DRIVER": "file",
    "QUEUE_CONNECTION": "sync"
  },
  "functions": {
    "public/index.php": {
      "runtime": "vercel-php@0.6.0"
    }
  }
}
```

---

## 🔧 **Database Seeding**

### **Step 1: Add Seeding to Build**
Update build command to include seeding:
```bash
composer install --no-dev && npm install && npm run build && php artisan migrate --force && php artisan db:seed --force
```

### **Step 2: Create Seeding Script**
Create `vercel-seed.sh`:
```bash
#!/bin/bash
php artisan migrate --force
php artisan db:seed --force
```

---

## 📊 **Cost Comparison**

| Provider | Free Tier | Paid Plans | Best For |
|----------|-----------|------------|----------|
| **PlanetScale** | ✅ 1GB storage | $29/month | Production apps |
| **Railway** | ✅ $5 credit | Pay per use | Development |
| **Clever Cloud** | ❌ | €9/month | European users |
| **AWS RDS** | ❌ | Pay per use | Enterprise |

---

## 🎯 **Recommended Setup**

### **For Development/Testing:**
1. **Use PlanetScale** (free tier)
2. **Setup takes 5 minutes**
3. **Automatic scaling**

### **For Production:**
1. **Use PlanetScale** (paid plan)
2. **Better performance**
3. **More storage**

---

## 🚀 **Quick Start with PlanetScale**

### **Step 1: Create Database**
```bash
# Install PlanetScale CLI
brew install planetscale/tap/pscale

# Login
pscale auth login

# Create database
pscale database create melb-print-hub
```

### **Step 2: Get Connection String**
```bash
# Get connection details
pscale connect melb-print-hub main
```

### **Step 3: Update Vercel**
1. **Go to Vercel dashboard**
2. **Add environment variables**
3. **Deploy**

---

## 🔍 **Troubleshooting**

### **Common Issues:**

#### **Connection Timeout**
- Check firewall settings
- Verify host/port
- Test connection locally

#### **Migration Fails**
- Check database permissions
- Verify connection string
- Run migrations manually

#### **Seeding Fails**
- Check database exists
- Verify table structure
- Check for duplicate entries

---

**Status**: **Ready for MySQL Setup** 🗄️  
**Recommended**: **PlanetScale** ⭐  
**Estimated Time**: **10 minutes** ⏱️
