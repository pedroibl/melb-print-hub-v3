# 🚀 **Vercel Deployment Guide - melbourneprinthub.com.au**

## 📋 **Current Status**
- ✅ **Code**: Pushed to GitHub (`pedroibl/melb-print-hub-lara`)
- ✅ **Domain**: `melbourneprinthub.com.au` registered with GoDaddy
- ✅ **Vercel Config**: `vercel.json` created
- 🚀 **Ready for deployment**

---

## 🎯 **Step-by-Step Vercel Deployment**

### **Step 1: Create Vercel Account**
1. **Go to**: https://vercel.com
2. **Sign up** with GitHub account
3. **Verify email** if required

### **Step 2: Import GitHub Repository**
1. **Click "New Project"**
2. **Import Git Repository**
3. **Select**: `pedroibl/melb-print-hub-lara`
4. **Framework Preset**: Select "Other" or "PHP"
5. **Root Directory**: Leave as `/` (default)

### **Step 3: Configure Build Settings**
```
Framework Preset: Other
Build Command: npm run build
Output Directory: public
Install Command: composer install --no-dev && npm install
```

### **Step 4: Environment Variables**
Add these environment variables in Vercel:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://melbourneprinthub.com.au
APP_KEY=base64:1Yj1bX1t71znIBgjUDHlxpYHnySnB6rIG/Jx62udswc=
DB_CONNECTION=mysql
DB_HOST=your_mysql_host_here
DB_PORT=3306
DB_DATABASE=your_database_name_here
DB_USERNAME=your_database_user_here
DB_PASSWORD=your_database_password_here
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Note**: You'll need to set up a MySQL database. Options include:
- **PlanetScale** (recommended for Vercel)
- **Railway**
- **Clever Cloud**
- **AWS RDS**
- **DigitalOcean Managed Databases**

### **Step 5: Deploy**
1. **Click "Deploy"**
2. **Wait for build** (2-5 minutes)
3. **Check deployment status**

---

## 🔧 **Post-Deployment Configuration**

### **Step 6: Add Custom Domain**
1. **Go to Project Settings**
2. **Click "Domains"**
3. **Add Domain**: `melbourneprinthub.com.au`
4. **Add Domain**: `www.melbourneprinthub.com.au`

### **Step 7: Configure DNS in GoDaddy**
1. **Log into GoDaddy**
2. **Go to Domain Management**
3. **Click "DNS" for melbourneprinthub.com.au**
4. **Add DNS Records**:

```
Type: CNAME
Name: @
Value: cname.vercel-dns.com
TTL: 600

Type: CNAME
Name: www
Value: cname.vercel-dns.com
TTL: 600
```

**Note**: Vercel will provide specific DNS values when you add the domain.

---

## 🛠️ **Vercel-Specific Optimizations**

### **Database Configuration**
Since Vercel is serverless, you'll need an external MySQL database:
- **Recommended**: PlanetScale (serverless MySQL, works great with Vercel)
- **Alternative**: Railway, Clever Cloud, AWS RDS, DigitalOcean
- **Database**: MySQL (external hosted)
- **Migrations**: Run automatically on deployment

### **File Storage**
- **Public files**: Stored in `/public` directory
- **Uploads**: Use external storage (AWS S3, Cloudinary)
- **Caching**: File-based cache in `/tmp`

### **Performance Optimizations**
- **CDN**: Automatic with Vercel
- **SSL**: Automatic HTTPS
- **Caching**: Automatic static asset caching

---

## 📊 **Deployment Checklist**

### **Pre-Deployment**
- [x] Code pushed to GitHub
- [x] Vercel account created
- [x] Repository imported
- [x] Build settings configured

### **Deployment**
- [ ] Deploy to Vercel
- [ ] Check build logs
- [ ] Verify deployment success
- [ ] Test basic functionality

### **Post-Deployment**
- [ ] Add custom domain
- [ ] Configure DNS in GoDaddy
- [ ] Test domain access
- [ ] Run database migrations
- [ ] Test all features

---

## 🔍 **Troubleshooting**

### **Common Issues**

#### **Build Fails**
```bash
# Check build logs in Vercel dashboard
# Common fixes:
# 1. Ensure composer.json exists
# 2. Check PHP version compatibility
# 3. Verify all dependencies are installed
```

#### **Domain Not Working**
```bash
# 1. Verify DNS propagation (can take 24-48 hours)
# 2. Check DNS records in GoDaddy
# 3. Ensure domain is added in Vercel
```

#### **Database Issues**
```bash
# 1. Check environment variables
# 2. Verify SQLite file permissions
# 3. Run migrations manually if needed
```

---

## 🎯 **Expected Results**

### **After Successful Deployment**
- ✅ **Website**: https://melbourneprinthub.com.au
- ✅ **SSL**: Automatic HTTPS
- ✅ **Performance**: CDN-enabled
- ✅ **Mobile**: Responsive design
- ✅ **Forms**: Quote and contact forms working

### **Features Available**
- ✅ Homepage with services overview
- ✅ Services page with 13 services
- ✅ Quote request form
- ✅ Contact form
- ✅ About page
- ✅ Mobile-responsive design

---

## 🚀 **Next Steps After Deployment**

### **1. Test Everything**
- [ ] Homepage loads correctly
- [ ] All pages accessible
- [ ] Forms submit successfully
- [ ] Mobile responsiveness
- [ ] SSL certificate working

### **2. Monitor Performance**
- [ ] Check Vercel analytics
- [ ] Monitor error logs
- [ ] Test loading speeds
- [ ] Verify mobile experience

### **3. Implement Issue #19**
- [ ] Add WhatsApp integration
- [ ] Test WhatsApp buttons
- [ ] Configure message templates

---

## 📱 **Mobile Testing**

### **Test URLs**
- **Main site**: https://melbourneprinthub.com.au
- **Services**: https://melbourneprinthub.com.au/services
- **Quote form**: https://melbourneprinthub.com.au/get-quote
- **Contact**: https://melbourneprinthub.com.au/contact

### **Mobile Features**
- ✅ Responsive design
- ✅ Touch-friendly navigation
- ✅ Fast loading on mobile
- ✅ SSL security

---

**Status**: **Ready for Vercel Deployment** 🚀  
**Estimated Time**: **20 minutes** ⏱️  
**Next Step**: **Create Vercel Account** 📝
