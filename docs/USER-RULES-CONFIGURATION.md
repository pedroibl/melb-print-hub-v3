# 🖨️ Melbourne Print Hub - User Rules Configuration Guide

## 📋 **Step-by-Step Implementation Guide**

This guide provides actionable steps to implement all User Rules in your Laravel project.

---

## 🚀 **Phase 1: Environment Setup (Week 1)**

### **Step 1.1: Development Environment Configuration**

```bash
# 1. Verify Laravel version (must be 12.26.4+)
php artisan --version

# 2. Check PHP version (must be 8.4.12+)
php --version

# 3. Install/update dependencies
composer install
npm install

# 4. Set up environment files
cp .env.example .env
php artisan key:generate
```

### **Step 1.2: Database Configuration**

```bash
# 1. Configure database in .env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# 2. Run migrations
php artisan migrate

# 3. Seed initial data
php artisan db:seed

# 4. Verify database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### **Step 1.3: Email Configuration**

```bash
# 1. Configure Yahoo SMTP in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=pedroibl@yahoo.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=pedroibl@yahoo.com
MAIL_FROM_NAME="Melbourne Print Hub"

# 2. Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## 🔒 **Phase 2: Security Implementation (Week 1-2)**

### **Step 2.1: CSRF Protection**

```php
// 1. Verify CSRF middleware in app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\VerifyCsrfToken::class,
        // ... other middleware
    ],
];

// 2. Add CSRF token to all forms
<form method="POST" action="/contact">
    @csrf
    <!-- form fields -->
</form>
```

### **Step 2.2: Input Validation**

```php
// 1. Create validation rules in app/Http/Requests/
// app/Http/Requests/ContactRequest.php
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:1000',
    ];
}

// 2. Apply in controllers
public function store(ContactRequest $request)
{
    // Validation automatically handled
    $validated = $request->validated();
}
```

### **Step 2.3: Security Headers**

```php
// 1. Add security headers in app/Http/Middleware/SecurityHeaders.php
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'DENY');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    
    return $response;
}

// 2. Register middleware in app/Http/Kernel.php
protected $middleware = [
    \App\Http\Middleware\SecurityHeaders::class,
];
```

---

## 🗄️ **Phase 3: Database Optimization (Week 2)**

### **Step 3.1: Model Validation**

```php
// 1. Add validation to models
// app/Models/ContactMessage.php
protected $fillable = ['name', 'email', 'message', 'status'];

public static function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:1000',
    ];
}

// 2. Add database indexes
// database/migrations/add_indexes_to_tables.php
public function up()
{
    Schema::table('contact_messages', function (Blueprint $table) {
        $table->index('email');
        $table->index('status');
        $table->index('created_at');
    });
}
```

### **Step 3.2: Data Protection**

```php
// 1. Add data encryption to sensitive fields
// app/Models/ContactMessage.php
protected $casts = [
    'email' => 'encrypted',
    'message' => 'encrypted',
];

// 2. Implement data retention policy
public function scopeOldMessages($query)
{
    return $query->where('created_at', '<', now()->subMonths(12));
}
```

---

## 📧 **Phase 4: Email System Setup (Week 2)**

### **Step 4.1: Email Templates**

```php
// 1. Create email templates
// resources/views/emails/contact-notification.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>New Contact Message - Melbourne Print Hub</title>
</head>
<body>
    <h2>New Contact Message Received</h2>
    <p><strong>From:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $contact->message }}</p>
    <hr>
    <p>Melbourne Print Hub | 0449 598 440</p>
</body>
</html>

// 2. Create Mailable class
// app/Mail/ContactNotification.php
class ContactNotification extends Mailable
{
    public $contact;
    
    public function __construct(ContactMessage $contact)
    {
        $this->contact = $contact;
    }
    
    public function build()
    {
        return $this->view('emails.contact-notification')
                    ->subject('New Contact Message - Melbourne Print Hub');
    }
}
```

### **Step 4.2: Email Automation**

```php
// 1. Set up email jobs
// app/Jobs/SendContactEmailJob.php
class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $contact;
    
    public function __construct(ContactMessage $contact)
    {
        $this->contact = $contact;
    }
    
    public function handle()
    {
        Mail::to('pedroibl@yahoo.com')
            ->send(new ContactNotification($this->contact));
    }
}

// 2. Dispatch in controller
public function store(ContactRequest $request)
{
    $contact = ContactMessage::create($request->validated());
    
    SendContactEmailJob::dispatch($contact);
    
    return redirect()->back()->with('success', 'Message sent successfully!');
}
```

---

## 🧪 **Phase 5: Testing Implementation (Week 3)**

### **Step 5.1: Unit Tests**

```php
// 1. Create model tests
// tests/Unit/ContactMessageTest.php
class ContactMessageTest extends TestCase
{
    public function test_contact_message_validation()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Test message',
        ];
        
        $contact = ContactMessage::create($data);
        
        $this->assertDatabaseHas('contact_messages', $data);
    }
}

// 2. Create feature tests
// tests/Feature/ContactFormTest.php
class ContactFormTest extends TestCase
{
    public function test_contact_form_submission()
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Test message',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
        ]);
    }
}
```

### **Step 5.2: Performance Testing**

```php
// 1. Add performance tests
// tests/Feature/PerformanceTest.php
class PerformanceTest extends TestCase
{
    public function test_homepage_loads_under_three_seconds()
    {
        $start = microtime(true);
        
        $response = $this->get('/');
        
        $end = microtime(true);
        $loadTime = $end - $start;
        
        $this->assertLessThan(3.0, $loadTime);
        $response->assertStatus(200);
    }
}
```

---

## 🎨 **Phase 6: Frontend Optimization (Week 3)**

### **Step 6.1: Responsive Design**

```css
/* 1. Add mobile-first CSS */
/* resources/css/app.css */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 768px) {
    .container {
        padding: 0 2rem;
    }
}

/* 2. Ensure accessibility */
.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.25rem;
    font-size: 1rem;
    line-height: 1.5;
    min-height: 44px; /* Touch target size */
}

.btn-primary:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}
```

### **Step 6.2: Loading Optimization**

```javascript
// 1. Optimize asset loading
// resources/js/app.js
import { createApp } from 'vue'
import App from './App.vue'

// Lazy load components
const ContactForm = () => import('./Components/ContactForm.vue')

createApp(App)
    .component('contact-form', ContactForm)
    .mount('#app')

// 2. Add loading indicators
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
        });
    });
});
```

---

## 📊 **Phase 7: Monitoring Setup (Week 4)**

### **Step 7.1: Error Tracking**

```php
// 1. Configure logging
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'daily'],
        'ignore_exceptions' => false,
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],

// 2. Add error handling
// app/Exceptions/Handler.php
public function register()
{
    $this->reportable(function (Throwable $e) {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }
    });
}
```

### **Step 7.2: Performance Monitoring**

```php
// 1. Add performance middleware
// app/Http/Middleware/PerformanceMonitor.php
class PerformanceMonitor
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        if ($duration > 3.0) {
            Log::warning('Slow page load', [
                'url' => $request->url(),
                'duration' => $duration,
            ]);
        }
        
        return $response;
    }
}
```

---

## 🔧 **Phase 8: Deployment Configuration (Week 4)**

### **Step 8.1: Production Environment**

```bash
# 1. Set production environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://melbourneprinthub.com

# 2. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Build assets
npm run build
```

### **Step 8.2: SSL Configuration**

```apache
# 1. Apache SSL configuration
<VirtualHost *:443>
    ServerName melbourneprinthub.com
    DocumentRoot /path/to/laravel/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /path/to/laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 📋 **Configuration Checklist**

### **✅ Week 1 Checklist**
- [ ] Laravel 12.26.4+ installed
- [ ] PHP 8.4.12+ verified
- [ ] Database migrations run
- [ ] Email configuration tested
- [ ] CSRF protection implemented
- [ ] Input validation added

### **✅ Week 2 Checklist**
- [ ] Security headers configured
- [ ] Model validation implemented
- [ ] Database indexes added
- [ ] Email templates created
- [ ] Email automation working
- [ ] Data encryption configured

### **✅ Week 3 Checklist**
- [ ] Unit tests written
- [ ] Feature tests implemented
- [ ] Performance tests added
- [ ] Responsive design verified
- [ ] Loading optimization complete
- [ ] Accessibility compliance checked

### **✅ Week 4 Checklist**
- [ ] Error tracking configured
- [ ] Performance monitoring active
- [ ] Production environment set
- [ ] SSL certificates installed
- [ ] Asset optimization complete
- [ ] Final testing passed

---

## 🚨 **Troubleshooting Guide**

### **Common Issues & Solutions**

```bash
# Issue: Email not sending
# Solution: Check SMTP configuration
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com'); });

# Issue: Database connection failed
# Solution: Verify database configuration
php artisan config:clear
php artisan migrate:status

# Issue: Performance problems
# Solution: Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📞 **Support & Next Steps**

### **After Configuration**
1. **Monitor Performance**: Check loading times and error rates
2. **Test All Features**: Verify forms, emails, and user flows
3. **Security Scan**: Run security vulnerability scans
4. **User Testing**: Test with real users and gather feedback

### **Maintenance Schedule**
- **Daily**: Check error logs and performance metrics
- **Weekly**: Review security updates and dependencies
- **Monthly**: Performance analysis and optimization
- **Quarterly**: Security audit and compliance review

---

**This configuration guide ensures all User Rules are properly implemented and maintained.** 🚀
