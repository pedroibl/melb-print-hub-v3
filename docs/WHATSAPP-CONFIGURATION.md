# 📱 WhatsApp Integration Configuration Guide

## 🎯 Overview

This guide explains how to configure the WhatsApp integration feature for Melbourne Print Hub. The integration provides users with instant communication capabilities directly through the website.

## 🔧 Environment Configuration

Add the following variables to your `.env` file:

```env
# WhatsApp Configuration
WHATSAPP_ENABLED=true
WHATSAPP_API_KEY=your_whatsapp_api_key_here
WHATSAPP_PHONE_NUMBER=+61449598440
WHATSAPP_BUSINESS_ID=your_whatsapp_business_id_here
WHATSAPP_DEFAULT_TEMPLATE=general
```

### Configuration Variables Explained

- **`WHATSAPP_ENABLED`**: Enable/disable WhatsApp integration (true/false)
- **`WHATSAPP_API_KEY`**: Your WhatsApp Business API key (from Twilio or WhatsApp Business API)
- **`WHATSAPP_PHONE_NUMBER`**: Your business WhatsApp number with country code
- **`WHATSAPP_BUSINESS_ID`**: Your WhatsApp Business account ID
- **`WHATSAPP_DEFAULT_TEMPLATE`**: Default message template to use

## 🚀 Setup Instructions

### 1. WhatsApp Business API Setup

#### Option A: Twilio WhatsApp API (Recommended for Development)
1. Sign up for a Twilio account at [twilio.com](https://www.twilio.com)
2. Navigate to WhatsApp in your Twilio Console
3. Get your API key and phone number
4. Add credentials to `.env` file

#### Option B: WhatsApp Business API (Production)
1. Apply for WhatsApp Business API access
2. Complete business verification
3. Get API credentials and phone number
4. Add credentials to `.env` file

### 2. Phone Number Configuration

Ensure your phone number is in international format:
```env
WHATSAPP_PHONE_NUMBER=+61449598440
```

### 3. Test Configuration

Test your WhatsApp integration:
```bash
# Test the integration
curl http://localhost:8000/whatsapp/test

# Get configuration
curl http://localhost:8000/whatsapp/config
```

## 📱 Available Components

### 1. WhatsAppButton
Basic WhatsApp button component with customizable variants.

```jsx
import WhatsAppButton from './Components/WhatsAppButton';

<WhatsAppButton 
    variant="default" 
    template="business_cards" 
    size="md" 
/>
```

**Props:**
- `variant`: "default", "outline", "ghost", "mobile"
- `template`: Message template to use
- `size`: "sm", "md", "lg"
- `customMessage`: Custom message text
- `showText`: Show/hide button text

### 2. MobileWhatsAppButton
Mobile-optimized sticky WhatsApp button.

```jsx
import MobileWhatsAppButton from './Components/MobileWhatsAppButton';

<MobileWhatsAppButton />
```

### 3. ServiceWhatsAppButton
Service-specific WhatsApp button with context-aware messaging.

```jsx
import ServiceWhatsAppButton from './Components/ServiceWhatsAppButton';

<ServiceWhatsAppButton 
    service="business_cards" 
    variant="outline" 
/>
```

### 4. WhatsAppFloatingButton
Advanced floating button with quick actions menu.

```jsx
import WhatsAppFloatingButton from './Components/WhatsAppFloatingButton';

<WhatsAppFloatingButton 
    position="bottom-right" 
    showQuickActions={true} 
/>
```

### 5. WhatsAppChatWidget
Interactive chat widget for advanced user experience.

```jsx
import WhatsAppChatWidget from './Components/WhatsAppChatWidget';

<WhatsAppChatWidget 
    showOnMobile={true} 
    showOnDesktop={true} 
/>
```

## 💬 Message Templates

### Available Templates

| Template Key | Message |
|--------------|---------|
| `business_cards` | "Hi! I need a quote for business cards. Can you help?" |
| `banners` | "Hi! I need banners for an event. What are my options?" |
| `flyers` | "Hi! I need flyers printed. Can you help with design and printing?" |
| `letterheads` | "Hi! I need professional letterheads. What are your options?" |
| `posters` | "Hi! I need posters printed. What sizes and materials do you offer?" |
| `corflute_signs` | "Hi! I need corflute signs for outdoor advertising. Can you help?" |
| `window_graphics` | "Hi! I need window graphics for my storefront. What are my options?" |
| `general` | "Hi! I have a printing question. Can you help?" |
| `urgent` | "URGENT: I need printing services quickly. Available?" |
| `quote` | "Hi! I submitted a quote request. Can we discuss it?" |
| `design_consultation` | "Hi! I need help with design for my printing project. Any tips?" |
| `order_status` | "Hi! Can I get an update on my order?" |

### Custom Messages

You can also use custom messages:

```jsx
<WhatsAppButton 
    customMessage="Hi! I need a custom quote for 500 business cards with gold foil printing." 
/>
```

## 🔌 API Endpoints

### WhatsApp Configuration
- `GET /whatsapp/config` - Get WhatsApp configuration
- `GET /whatsapp/templates` - Get all message templates

### Service-Specific URLs
- `GET /whatsapp/service/{service}` - Get WhatsApp URL for specific service
- `GET /whatsapp/urgent` - Get WhatsApp URL for urgent requests
- `GET /whatsapp/quote-followup` - Get WhatsApp URL for quote follow-up
- `GET /whatsapp/order-status` - Get WhatsApp URL for order status

### Testing
- `GET /whatsapp/test` - Test WhatsApp integration

## 🎨 Customization

### Button Styling

Customize button appearance using Tailwind CSS classes:

```jsx
<WhatsAppButton 
    className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full" 
/>
```

### Message Templates

Add custom message templates in `config/whatsapp.php`:

```php
'templates' => [
    'custom_service' => "Hi! I need help with custom service. Can you assist?",
    // ... existing templates
],
```

### Service Mapping

Map services to templates in `ServiceWhatsAppButton.jsx`:

```jsx
const templates = {
    'custom_service': 'custom_service',
    // ... existing mappings
};
```

## 🧪 Testing

### 1. Test WhatsApp Button
1. Navigate to any page with WhatsApp buttons
2. Click the WhatsApp button
3. Verify WhatsApp opens with pre-filled message
4. Check message content is correct

### 2. Test Mobile Responsiveness
1. Test on mobile devices
2. Verify sticky button positioning
3. Check touch interactions
4. Test different screen sizes

### 3. Test API Endpoints
1. Test all WhatsApp API endpoints
2. Verify response format
3. Check error handling
4. Test with invalid parameters

## 🚨 Troubleshooting

### Common Issues

#### Button Not Opening WhatsApp
- Check phone number format in `.env`
- Verify WhatsApp is installed on device
- Test with different browsers

#### Message Not Pre-filling
- Check template configuration
- Verify template keys match
- Test with custom messages

#### Mobile Button Not Visible
- Check responsive breakpoints
- Verify z-index values
- Test on different devices

#### API Endpoints Not Working
- Check route configuration
- Verify controller exists
- Check Laravel logs for errors

### Debug Commands

```bash
# Check WhatsApp configuration
php artisan tinker
>>> app(App\Services\WhatsAppService::class)->getPhoneNumber()

# Test WhatsApp service
>>> app(App\Services\WhatsAppService::class)->generateWhatsAppUrl('Test message')

# Check routes
php artisan route:list | grep whatsapp
```

## 🔒 Security Considerations

### API Key Security
- Never commit API keys to version control
- Use environment variables for sensitive data
- Rotate API keys regularly
- Limit API key permissions

### User Privacy
- Don't store WhatsApp conversations
- Implement GDPR compliance
- Provide opt-out options
- Clear privacy policy

### Rate Limiting
- Implement API rate limiting
- Monitor API usage
- Set reasonable limits
- Handle errors gracefully

## 📊 Analytics & Monitoring

### Track WhatsApp Usage
- Monitor button click rates
- Track message template usage
- Measure conversion rates
- Analyze user engagement

### Performance Monitoring
- Monitor API response times
- Track error rates
- Monitor mobile performance
- Check accessibility compliance

## 🚀 Future Enhancements

### Planned Features
1. **Webhook Integration** - Handle incoming messages
2. **Chat History** - Track conversation history
3. **Automated Responses** - AI-powered responses
4. **Analytics Dashboard** - Business intelligence
5. **Multi-language Support** - International expansion

### Integration Opportunities
1. **CRM Integration** - Customer relationship management
2. **Order Management** - Direct order placement
3. **Payment Processing** - WhatsApp Pay integration
4. **File Sharing** - Design file uploads
5. **Appointment Booking** - Schedule consultations

## 📞 Support

For technical support or questions about WhatsApp integration:

- **Email**: info@melbourneprinthub.com
- **Phone**: 0449 598 440
- **Documentation**: This guide and project documentation
- **GitHub Issues**: Report bugs or feature requests

---

**WhatsApp Integration Status**: ✅ **IMPLEMENTED**  
**Last Updated**: September 2025  
**Version**: 1.0.0
