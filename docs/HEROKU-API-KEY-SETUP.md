# 🔑 **How to Add API Key to Heroku CLI**

## 📋 **Method 1: Using Heroku CLI Login (Recommended)**

### **Step 1: Get Your API Key**
1. **Go to**: https://dashboard.heroku.com/account
2. **Scroll down** to "API Key" section
3. **Click "Reveal"** to show your API key
4. **Copy the API key** (it looks like: `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`)

### **Step 2: Login with API Key**
```bash
# Method 1: Interactive login (easiest)
heroku login

# Method 2: Direct API key login
heroku login -i
# Then enter your email and API key when prompted
```

### **Step 3: Verify Login**
```bash
# Check if you're logged in
heroku auth:whoami

# This should show your email address
```

---

## 🔧 **Method 2: Set API Key Environment Variable**

### **Step 1: Set Environment Variable**
```bash
# Set the API key as environment variable
export HEROKU_API_KEY=your_api_key_here

# For permanent storage (add to ~/.zshrc)
echo 'export HEROKU_API_KEY=your_api_key_here' >> ~/.zshrc
source ~/.zshrc
```

### **Step 2: Verify Setup**
```bash
# Check if API key is set
echo $HEROKU_API_KEY

# Test Heroku CLI
heroku apps
```

---

## 🔐 **Method 3: Using Heroku CLI Config**

### **Step 1: Set API Key in Config**
```bash
# Set API key in Heroku CLI config
heroku config:set HEROKU_API_KEY=your_api_key_here

# Or set it globally
heroku config:set HEROKU_API_KEY=your_api_key_here --global
```

### **Step 2: Verify Configuration**
```bash
# Check config
heroku config

# Test connection
heroku apps
```

---

## 🚀 **Method 4: Direct API Key Usage**

### **Step 1: Use API Key Directly**
```bash
# Use API key with commands
HEROKU_API_KEY=your_api_key_here heroku apps

# Or for specific commands
HEROKU_API_KEY=your_api_key_here heroku create my-app
```

---

## 🔍 **Troubleshooting**

### **Common Issues:**

#### **"API key not found"**
```bash
# Check if API key is set
echo $HEROKU_API_KEY

# Re-login if needed
heroku logout
heroku login
```

#### **"Authentication failed"**
```bash
# Clear stored credentials
heroku logout

# Remove stored API key
unset HEROKU_API_KEY

# Re-login
heroku login
```

#### **"Permission denied"**
```bash
# Check your account permissions
heroku auth:whoami

# Verify API key is correct
# Go to https://dashboard.heroku.com/account
```

---

## 📱 **For CI/CD (GitHub Actions, etc.)**

### **GitHub Actions Example:**
```yaml
name: Deploy to Heroku
on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    
    - name: Deploy to Heroku
      uses: akhileshns/heroku-deploy@v3.12.14
      with:
        heroku_api_key: ${{ secrets.HEROKU_API_KEY }}
        heroku_app_name: "your-app-name"
        heroku_email: "your-email@example.com"
```

### **Environment Variables:**
```bash
# Set in your CI/CD environment
HEROKU_API_KEY=your_api_key_here
HEROKU_EMAIL=your_email@example.com
```

---

## 🔒 **Security Best Practices**

### **Do:**
- ✅ **Use environment variables** for API keys
- ✅ **Never commit API keys** to Git
- ✅ **Rotate API keys** regularly
- ✅ **Use least privilege** access

### **Don't:**
- ❌ **Hardcode API keys** in scripts
- ❌ **Share API keys** in documentation
- ❌ **Use API keys** in public repositories
- ❌ **Store API keys** in plain text files

---

## 🎯 **Quick Setup Commands**

### **Complete Setup:**
```bash
# 1. Get API key from dashboard
# 2. Login with CLI
heroku login

# 3. Verify login
heroku auth:whoami

# 4. Test connection
heroku apps
```

### **For Automation:**
```bash
# Set API key
export HEROKU_API_KEY=your_api_key_here

# Test
heroku apps
```

---

## 🔗 **Useful Commands**

```bash
# Check login status
heroku auth:whoami

# List your apps
heroku apps

# Check API key
echo $HEROKU_API_KEY

# Logout
heroku logout

# Login again
heroku login
```

---

**Status**: **API Key Setup Guide** 🔑  
**Recommended**: **Method 1 (Interactive Login)** ⭐  
**Security**: **Environment Variables** 🔒  
**Setup Time**: **2 minutes** ⏱️

