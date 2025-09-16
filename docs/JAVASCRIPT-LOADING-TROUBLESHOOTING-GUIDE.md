# JavaScript Loading Troubleshooting Guide

## Overview

This document details the comprehensive troubleshooting process undertaken to resolve JavaScript loading issues on the Melbourne Print Hub production site. The main issue was a `net::ERR_FAILED` error occurring when loading JavaScript files, despite the server returning HTTP 200 OK responses.

## Initial Problem Description

**Error:** `GET https://melbourneprinthub.com.au/build/assets/app-GIQoBvXJ.js net::ERR_FAILED`

**Symptoms:**
- JavaScript files returning 200 OK status but failing to load in browser
- Console showing `net::ERR_FAILED` errors
- Website not functioning properly due to missing JavaScript
- Assets accessible via direct URL but blocked by browser

## Root Cause Analysis

The issue was identified as a **CORS (Cross-Origin Resource Sharing)** problem. The browser was blocking JavaScript file requests due to missing or invalid CORS headers, even though the server was responding successfully.

## Troubleshooting Steps Taken

### 1. Initial URL Visualization Issues

**Problem:** URLs not visualizing correctly on Heroku production
**Solution:** Configured Laravel's HTTPS detection and URL generation

**Files Modified:**
- `config/app.php` - Set application URL
- `app/Http/Middleware/TrustProxies.php` - Configured proxy handling
- `app/Providers/AppServiceProvider.php` - Added HTTPS scheme forcing

### 2. Asset Loading Problems

**Problem:** JavaScript files returning 404 on Heroku
**Root Cause:** `public/build` directory in `.gitignore`
**Solution:** Force-added built assets to git deployment

```bash
git add -f public/build/
git commit -m "Add built assets to deployment"
git push heroku main
```

### 3. Content Security Policy (CSP) Issues

**Problem:** CSP blocking stylesheet and script loading
**Error:** `Refused to load the stylesheet/script because it violates Content Security Policy`

**Solutions Applied:**

#### A. Updated `.htaccess` File
```apache
# Added melbourneprinthub.com.au to CSP directives
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://melbourneprinthub.com.au; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://melbourneprinthub.com.au; style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://melbourneprinthub.com.au; font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:; frame-src 'none'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests;"
```

#### B. Updated SecurityHeaders Middleware
```php
// Added melbourneprinthub.com.au to script-src directive
$csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://melbourneprinthub.com.au; ...";
```

### 4. Browser Extension Interference

**Problem:** Chrome extension errors causing `chrome-extension://invalid/` errors
**Solution:** Created debugging tools to identify problematic extensions

**Debugging Tools Created:**
- `public/chrome-extension-debug.html` - General extension debugging
- `public/chrome-extension-specific-debug.html` - Specific extension targeting
- `resources/js/chrome-extension-specific-debug.js` - Console debugging script

**Resolution:** User deactivated browser extensions, resolving the issue

### 5. Vite Configuration Issues

**Problem:** Complex Vite configuration causing conflicts with Heroku's HTTPS handling
**Solution:** Reverted to simple Vite configuration based on previous working commits

**Final Working Configuration:**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ],
});
```

### 6. CORS Headers Implementation

**Problem:** Missing CORS headers causing `net::ERR_FAILED` despite 200 OK responses
**Solution:** Added comprehensive CORS configuration

#### A. Updated `.htaccess` with CORS Headers
```apache
# CORS Headers for JavaScript and CSS assets
<FilesMatch "\.(js|css)$">
    Header always set Access-Control-Allow-Origin "*"
    Header always set Access-Control-Allow-Methods "GET, OPTIONS"
    Header always set Access-Control-Allow-Headers "Origin, X-Requested-With, Content-Type, Accept"
    Header always set Access-Control-Max-Age "86400"
</FilesMatch>

# Handle preflight requests
RewriteEngine On
RewriteCond %{REQUEST_METHOD} OPTIONS
RewriteRule ^(.*)$ $1 [R=200,L]
```

#### B. Updated SecurityHeaders Middleware
```php
// CORS Headers for JavaScript and CSS assets
if (preg_match('/\.(js|css)$/', $request->path())) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    $response->headers->set('Access-Control-Max-Age', '86400');
}
```

## Testing and Verification

### 1. Server Response Testing
```bash
# Test JavaScript file accessibility
curl -I https://melbourneprinthub.com.au/build/assets/app-GIQoBvXJ.js

# Test CORS headers
curl -I -H "Origin: https://example.com" https://melbourneprinthub.com.au/build/assets/app-GIQoBvXJ.js

# Test OPTIONS preflight request
curl -X OPTIONS -H "Origin: https://example.com" -H "Access-Control-Request-Method: GET" -I https://melbourneprinthub.com.au/build/assets/app-GIQoBvXJ.js
```

### 2. Debug Tools Created
- `public/javascript-debug-test.html` - Comprehensive JavaScript loading test page
- `public/javascript-loading-test.html` - Network timing analysis
- Console debugging scripts for browser extension issues

### 3. Expected CORS Headers
```
access-control-allow-origin: *
access-control-allow-methods: GET, OPTIONS
access-control-allow-headers: Origin, X-Requested-With, Content-Type, Accept
access-control-max-age: 86400
```

## Key Lessons Learned

### 1. CORS Headers Are Critical
- Even with HTTP 200 OK responses, missing CORS headers can cause `net::ERR_FAILED`
- Browsers enforce CORS policies strictly for security reasons
- Preflight OPTIONS requests must be handled properly

### 2. Heroku HTTPS Handling
- Heroku handles HTTPS automatically at the load balancer level
- Explicit HTTPS forcing in code can cause conflicts
- Simple Vite configuration works better than complex setups

### 3. Asset Deployment
- Built assets must be included in git deployment
- `.gitignore` can prevent necessary files from being deployed
- Force-adding assets may be necessary: `git add -f public/build/`

### 4. Content Security Policy
- CSP can block assets even when they're accessible directly
- Domain must be explicitly allowed in relevant CSP directives
- Both `.htaccess` and middleware can set CSP headers

### 5. Browser Extensions
- Browser extensions can interfere with network requests
- `chrome-extension://invalid/` errors indicate extension issues
- Disabling extensions is a valid troubleshooting step

## Final Working Configuration

### Environment Variables
```bash
APP_URL=https://melbourneprinthub.com.au
ASSET_URL=https://melbourneprinthub.com.au
```

### Key Files Configuration

#### `public/.htaccess`
- CORS headers for `.js` and `.css` files
- Preflight request handling
- Updated CSP directives

#### `app/Http/Middleware/SecurityHeaders.php`
- Conditional CORS headers for assets
- Updated CSP with domain inclusion

#### `vite.config.js`
- Simple configuration without HTTPS forcing
- Standard Laravel Vite plugin setup

#### `app/Providers/AppServiceProvider.php`
- Basic URL configuration for production
- No explicit HTTPS forcing

## Deployment Commands

```bash
# Build assets
npm run build

# Force add built assets
git add -f public/build/

# Commit and deploy
git commit -m "Add built assets and fix CORS headers"
git push heroku main

# Verify deployment
curl -I https://melbourneprinthub.com.au/build/assets/app-GIQoBvXJ.js
```

## Monitoring and Maintenance

### Regular Checks
1. Verify CORS headers are present on asset requests
2. Check that built assets are properly deployed
3. Monitor for CSP violations in browser console
4. Ensure Vite configuration remains simple and compatible

### Troubleshooting Checklist
- [ ] Assets accessible via direct URL
- [ ] CORS headers present in response
- [ ] CSP directives include necessary domains
- [ ] Built assets included in deployment
- [ ] Vite configuration is simple and compatible
- [ ] No browser extension interference
- [ ] HTTPS URLs used consistently

## Conclusion

The JavaScript loading issue was successfully resolved by implementing proper CORS headers and ensuring all assets are properly deployed. The key was understanding that `net::ERR_FAILED` with 200 OK status indicates a CORS policy violation rather than a server error.

The solution involved:
1. Adding CORS headers to both Apache and Laravel middleware
2. Ensuring built assets are deployed
3. Simplifying Vite configuration
4. Updating CSP directives
5. Creating comprehensive debugging tools

This troubleshooting process provides a template for resolving similar asset loading issues in the future.
