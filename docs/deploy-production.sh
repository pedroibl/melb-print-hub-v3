#!/bin/bash

# 🚀 Melbourne Print Hub - Production Deployment Script
# This script helps deploy your Laravel application to production

echo "🚀 Melbourne Print Hub - Production Deployment"
echo "=============================================="

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ This script must be run as root (use sudo)"
    exit 1
fi

echo "✅ Running as root"

# Update system
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "📦 Installing required packages..."
apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd composer git curl unzip

# Install Node.js and npm
echo "📦 Installing Node.js and npm..."
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Install Certbot for SSL
echo "📦 Installing Certbot for SSL..."
apt install -y certbot python3-certbot-nginx

# Create web directory
echo "📁 Creating web directory..."
mkdir -p /var/www/melbourneprinthub
cd /var/www/melbourneprinthub

# Clone repository (replace with your actual repository URL)
echo "📥 Cloning repository..."
git clone https://github.com/your-username/laravel-project.git .

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Copy environment file
echo "⚙️ Configuring environment..."
cp .env.example .env

# Generate application key
php artisan key:generate

# Set up database
echo "🗄️ Setting up database..."
mysql -e "CREATE DATABASE IF NOT EXISTS melb_print_hub;"
mysql -e "CREATE USER IF NOT EXISTS 'melb_print_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';"
mysql -e "GRANT ALL PRIVILEGES ON melb_print_hub.* TO 'melb_print_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Update .env with database credentials
sed -i 's/DB_DATABASE=.*/DB_DATABASE=melb_print_hub/' .env
sed -i 's/DB_USERNAME=.*/DB_USERNAME=melb_print_user/' .env
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=your_secure_password_here/' .env
sed -i 's/APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=.*/APP_DEBUG=false/' .env
sed -i 's/APP_URL=.*/APP_URL=https:\/\/melbourneprinthub.com.au/' .env

# Run migrations and seeders
echo "🗄️ Running database migrations..."
php artisan migrate --force
php artisan db:seed --force

# Install Node.js dependencies and build assets
echo "📦 Installing Node.js dependencies..."
npm install
npm run build

# Set proper permissions
echo "🔐 Setting file permissions..."
chown -R www-data:www-data /var/www/melbourneprinthub
chmod -R 755 /var/www/melbourneprinthub
chmod -R 775 /var/www/melbourneprinthub/storage
chmod -R 775 /var/www/melbourneprinthub/bootstrap/cache

# Configure Nginx
echo "🌐 Configuring Nginx..."
cat > /etc/nginx/sites-available/melbourneprinthub << 'EOF'
server {
    listen 80;
    server_name melbourneprinthub.com.au www.melbourneprinthub.com.au;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name melbourneprinthub.com.au www.melbourneprinthub.com.au;
    
    ssl_certificate /etc/letsencrypt/live/melbourneprinthub.com.au/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/melbourneprinthub.com.au/privkey.pem;
    
    root /var/www/melbourneprinthub/public;
    index index.php index.html;
    
    # Security headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Enable the site
ln -sf /etc/nginx/sites-available/melbourneprinthub /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart Nginx
systemctl restart nginx
systemctl enable nginx

# Restart PHP-FPM
systemctl restart php8.1-fpm
systemctl enable php8.1-fpm

echo "✅ Basic deployment complete!"
echo ""
echo "🔒 Next steps:"
echo "1. Point DNS to this server's IP address"
echo "2. Run: sudo certbot --nginx -d melbourneprinthub.com.au"
echo "3. Test the website"
echo ""
echo "🌐 Your website will be available at:"
echo "   http://melbourneprinthub.com.au (will redirect to HTTPS)"
echo "   https://melbourneprinthub.com.au"
echo ""
echo "📱 For mobile testing, use the HTTPS URL"
