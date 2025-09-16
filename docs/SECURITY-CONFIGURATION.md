# 🔒 Security Configuration Guide for Melbourne Print Hub

## Environment Variables for Security

Add these variables to your `.env` file for enhanced security:

```env
# Security Configuration
APP_ENV=production
APP_DEBUG=false
APP_URL=https://melbourneprinthub.com

# Session Security
SESSION_DRIVER=database
SESSION_LIFETIME=60
SESSION_EXPIRE_ON_CLOSE=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# HTTPS Enforcement
FORCE_HTTPS=true

# Security Headers
SECURITY_HEADERS_ENABLED=true

# CSRF Protection
CSRF_PROTECTION=true
CSRF_TOKEN_LIFETIME=60

# Database Security
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=melb_print_hub
DB_USERNAME=melb_print_user
DB_PASSWORD=your_secure_password_here

# Email Security
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=pedroibl@yahoo.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=pedroibl@yahoo.com
MAIL_FROM_NAME="Melbourne Print Hub"

# Logging Security
LOG_CHANNEL=stack
LOG_LEVEL=warning
LOG_DAYS=30

# Cache Security
CACHE_DRIVER=file
CACHE_PREFIX=melb_print_hub_

# Queue Security
QUEUE_CONNECTION=sync
QUEUE_FAILED_DRIVER=database-uuids

# File Upload Security
FILESYSTEM_DISK=local
UPLOAD_MAX_FILESIZE=10M
POST_MAX_SIZE=10M
```

## Security Best Practices

### 1. SSL Certificate Management
- Use Let's Encrypt for free SSL certificates
- Set up automatic renewal
- Monitor certificate expiration
- Use strong cipher suites

### 2. Database Security
- Use strong passwords
- Limit database user privileges
- Enable SSL/TLS for database connections
- Regular database backups

### 3. Session Security
- Short session lifetimes
- Secure cookie settings
- Session regeneration
- CSRF token protection

### 4. File Upload Security
- Validate file types
- Limit file sizes
- Scan for malware
- Secure storage locations

### 5. Logging and Monitoring
- Monitor security events
- Log authentication attempts
- Track file access
- Alert on suspicious activity

## SSL Certificate Setup

### Using Let's Encrypt (Recommended)

1. Install Certbot:
```bash
# macOS
brew install certbot

# Ubuntu/Debian
sudo apt-get install certbot
```

2. Obtain SSL Certificate:
```bash
sudo certbot --apache -d melbourneprinthub.com -d www.melbourneprinthub.com
```

3. Set up Auto-renewal:
```bash
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Manual SSL Certificate

1. Purchase SSL certificate from trusted CA
2. Install certificate files
3. Configure web server
4. Test SSL configuration

## Security Testing

### SSL Testing
```bash
# Test SSL certificate
openssl s_client -connect melbourneprinthub.com:443 -servername melbourneprinthub.com

# Check certificate expiration
echo | openssl s_client -servername melbourneprinthub.com -connect melbourneprinthub.com:443 2>/dev/null | openssl x509 -noout -dates
```

### Security Headers Testing
```bash
# Test security headers
curl -I https://melbourneprinthub.com

# Check specific headers
curl -I https://melbourneprinthub.com | grep -E "(Strict-Transport-Security|Content-Security-Policy|X-Frame-Options|X-Content-Type-Options)"
```

### Vulnerability Scanning
```bash
# Install security scanner
npm install -g snyk

# Scan for vulnerabilities
snyk test
```

## Monitoring and Maintenance

### Regular Security Tasks
- [ ] Check SSL certificate expiration
- [ ] Review security logs
- [ ] Update dependencies
- [ ] Test security headers
- [ ] Monitor for suspicious activity
- [ ] Backup security configurations

### Emergency Procedures
- [ ] Incident response plan
- [ ] Contact information for security team
- [ ] Backup and recovery procedures
- [ ] Communication protocols

## Compliance

### GDPR Compliance
- [ ] Data protection impact assessment
- [ ] Privacy policy updates
- [ ] User consent management
- [ ] Data retention policies
- [ ] Right to be forgotten implementation

### PCI DSS (if handling payments)
- [ ] Secure payment processing
- [ ] Data encryption
- [ ] Access controls
- [ ] Regular security assessments

---

**Last Updated**: September 2025  
**Version**: 1.0.0  
**Status**: Production Ready
