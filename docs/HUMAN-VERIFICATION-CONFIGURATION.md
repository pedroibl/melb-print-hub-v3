# Human Verification Configuration Guide

This guide explains how to configure the human verification system for Melbourne Print Hub forms.

## Overview

The human verification system includes:
- **Google reCAPTCHA v3** (invisible) - Primary verification method
- **hCaptcha** - Alternative verification method
- **Honeypot fields** - Hidden spam traps
- **Rate limiting** - IP-based submission limits
- **Content pattern analysis** - Spam content detection
- **Timing validation** - Prevents instant submissions

## Environment Variables

Add the following variables to your `.env` file:

```env
# CAPTCHA Configuration
CAPTCHA_TYPE=recaptcha
NOCAPTCHA_SECRET=your_recaptcha_secret_key
NOCAPTCHA_SITEKEY=your_recaptcha_site_key
HCAPTCHA_SECRET=your_hcaptcha_secret_key
HCAPTCHA_SITEKEY=your_hcaptcha_site_key
RECAPTCHA_MIN_SCORE=0.5
```

## Google reCAPTCHA v3 Setup

1. **Get API Keys:**
   - Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
   - Click "Create" to register a new site
   - Choose "reCAPTCHA v3"
   - Add your domain(s)
   - Accept the terms and submit

2. **Configure Keys:**
   - Copy the **Site Key** to `NOCAPTCHA_SITEKEY`
   - Copy the **Secret Key** to `NOCAPTCHA_SECRET`

3. **Score Threshold:**
   - `RECAPTCHA_MIN_SCORE=0.5` (0.0 = bot, 1.0 = human)
   - Adjust based on your needs (0.3-0.7 recommended)

## hCaptcha Setup (Alternative)

1. **Get API Keys:**
   - Go to [hCaptcha Dashboard](https://dashboard.hcaptcha.com/)
   - Create a new site
   - Choose "Invisible" or "Checkbox" mode
   - Add your domain(s)

2. **Configure Keys:**
   - Copy the **Site Key** to `HCAPTCHA_SITEKEY`
   - Copy the **Secret Key** to `HCAPTCHA_SECRET`

## Verification Types

Set `CAPTCHA_TYPE` in your `.env`:

- `recaptcha` - Use Google reCAPTCHA v3 (default)
- `hcaptcha` - Use hCaptcha
- `none` - Disable CAPTCHA (not recommended for production)

## Rate Limiting Configuration

The system includes automatic rate limiting:

- **Contact Form:** 5 submissions per hour per IP
- **Quote Form:** 3 submissions per hour per IP
- **General:** 10 submissions per hour per IP

## Security Features

### Honeypot Fields
Hidden fields that catch bots:
- `website` - Hidden text field
- `phone_number` - Hidden text field  
- `company` - Hidden text field
- `url` - Hidden text field

### Content Pattern Detection
Blocks submissions containing:
- Spam keywords (viagra, casino, etc.)
- Promotional phrases (click here, buy now, etc.)
- Financial scams (free money, earn money, etc.)
- Crypto/forex content
- SEO spam terms

### Timing Validation
- Minimum 3 seconds between form load and submission
- Maximum 30 minutes between form load and submission
- Prevents instant bot submissions

## Testing

### Development Testing
For development, you can use these test keys:

```env
# Test Keys (for development only)
NOCAPTCHA_SECRET=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
NOCAPTCHA_SITEKEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
```

### Production Checklist
- [ ] Set up real reCAPTCHA/hCaptcha keys
- [ ] Configure proper domain restrictions
- [ ] Test with real users
- [ ] Monitor logs for false positives
- [ ] Adjust score thresholds if needed

## Monitoring

The system logs all verification attempts:

```bash
# View verification logs
tail -f storage/logs/laravel.log | grep "verification"
```

## Troubleshooting

### Common Issues

1. **CAPTCHA not loading:**
   - Check site key configuration
   - Verify domain is whitelisted
   - Check browser console for errors

2. **False positives:**
   - Lower the `RECAPTCHA_MIN_SCORE` value
   - Check content pattern filters
   - Review timing validation settings

3. **Rate limiting issues:**
   - Check IP address detection
   - Verify middleware configuration
   - Review rate limit settings

### Debug Mode

Enable debug logging by adding to `.env`:

```env
LOG_LEVEL=debug
```

## Performance Considerations

- reCAPTCHA v3 is invisible and doesn't impact UX
- Rate limiting uses Redis/cache for performance
- Content analysis is lightweight and fast
- Honeypot fields have zero performance impact

## Security Best Practices

1. **Never expose secret keys** in frontend code
2. **Use HTTPS** in production
3. **Regularly rotate** CAPTCHA keys
4. **Monitor** for unusual patterns
5. **Keep dependencies** updated
6. **Test** with various bot scenarios

## Support

For issues with:
- **reCAPTCHA:** [Google reCAPTCHA Support](https://developers.google.com/recaptcha/docs/faq)
- **hCaptcha:** [hCaptcha Support](https://docs.hcaptcha.com/)
- **Laravel:** [Laravel Documentation](https://laravel.com/docs)
