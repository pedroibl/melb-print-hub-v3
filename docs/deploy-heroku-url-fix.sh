#!/bin/bash

# Heroku Production Deployment Script
# This script ensures proper configuration for URL visualization on Heroku

echo "🚀 Deploying to Heroku Production..."

# Set environment variables for Heroku
echo "📝 Setting Heroku environment variables..."

# Force HTTPS and proper URL configuration
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://melbourneprinthub.com.au
heroku config:set SESSION_SECURE_COOKIE=true
heroku config:set SESSION_SAME_SITE=lax
heroku config:set LOG_CHANNEL=stack

# Database configuration (adjust as needed)
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=your-heroku-db-host
heroku config:set DB_PORT=3306
heroku config:set DB_DATABASE=your-heroku-db-name
heroku config:set DB_USERNAME=your-heroku-db-user
heroku config:set DB_PASSWORD=your-heroku-db-password

# Mail configuration
heroku config:set MAIL_MAILER=smtp
heroku config:set MAIL_HOST=your-mail-host
heroku config:set MAIL_PORT=587
heroku config:set MAIL_USERNAME=your-mail-username
heroku config:set MAIL_PASSWORD=your-mail-password
heroku config:set MAIL_ENCRYPTION=tls
heroku config:set MAIL_FROM_ADDRESS=noreply@melbourneprinthub.com.au
heroku config:set MAIL_FROM_NAME="Melbourne Print Hub"

# WhatsApp configuration
heroku config:set WHATSAPP_ENABLED=true
heroku config:set WHATSAPP_PHONE_NUMBER=+61412345678
heroku config:set WHATSAPP_API_KEY=your-whatsapp-api-key
heroku config:set WHATSAPP_INSTANCE_ID=your-instance-id

# Clear application cache
echo "🧹 Clearing application cache..."
heroku run php artisan config:clear
heroku run php artisan cache:clear
heroku run php artisan view:clear
heroku run php artisan route:clear

# Run migrations
echo "🗄️ Running database migrations..."
heroku run php artisan migrate --force

# Build assets
echo "🏗️ Building frontend assets..."
npm run build

# Deploy to Heroku
echo "📤 Deploying to Heroku..."
git add .
git commit -m "Fix URL visualization for Heroku production"
git push heroku main

echo "✅ Deployment complete!"
echo "🌐 Test URL generation at: https://melbourneprinthub.com.au/test-urls"
echo "🔧 Check logs with: heroku logs --tail"
