# Melbourne Print Hub

A modern web application for Melbourne Print Hub, built with Laravel and React.

## 🚀 Quick Start

```bash
# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
npm run dev
```

## 📚 Documentation

Comprehensive documentation is available in the [`docs/`](./docs/) directory:

- [Project Overview](./docs/PROJECT-OVERVIEW.md) - Complete project overview
- [Getting Started](./docs/GETTING-STARTED.md) - Setup and installation guide
- [Development Workflow](./docs/dev-workflow.md) - Development practices and workflow
- [JavaScript Loading Troubleshooting](./docs/JAVASCRIPT-LOADING-TROUBLESHOOTING-GUIDE.md) - Complete troubleshooting guide for asset loading issues
- [JavaScript Loading Quick Reference](./docs/JAVASCRIPT-LOADING-QUICK-REFERENCE.md) - Quick reference for JavaScript loading fixes
- [Deployment Guides](./docs/) - Heroku and Vercel deployment instructions
- [Configuration Guides](./docs/) - Security, email, WhatsApp, and other configurations

## 🛠️ Features

- **Contact Forms** - Human verification and spam protection
- **Quote Requests** - Professional quote request system
- **Email Integration** - HTML email templates and testing
- **WhatsApp Integration** - Direct messaging capabilities
- **Security** - reCAPTCHA, CSP headers, and security best practices
- **Responsive Design** - Mobile-first approach with Tailwind CSS

## 🌐 Production

- **Live Site**: [melbourneprinthub.com.au](https://melbourneprinthub.com.au)
- **Deployment**: Heroku with automatic HTTPS
- **Database**: SQLite (development) / MySQL (production)

## 📋 Requirements

- PHP 8.4+
- Node.js 18+
- Composer
- npm/yarn

## 📄 License

This project is proprietary software for Melbourne Print Hub.
