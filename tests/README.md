# Melbourne Print Hub - Form Performance Test Suite

This comprehensive test suite validates the functionality and performance of the Melbourne Print Hub contact and quote forms using Laravel Dusk browser automation.

## 🎯 **Test Coverage**

### **Contact Form Tests** (`ContactFormTest.php`)
- ✅ Page load performance (target: <3s)
- ✅ Form validation (required fields, email format)
- ✅ Successful submission with database verification
- ✅ Special characters handling (UTF-8, emojis)
- ✅ Performance under load (target: <4s average)
- ✅ Accessibility compliance (labels, autocomplete)
- ✅ Mobile responsiveness (iPhone SE dimensions)
- ✅ Error handling and form reset
- ✅ CSRF protection verification
- ✅ Rate limiting functionality

### **Quote Form Tests** (`QuoteFormTest.php`)
- ✅ Page load performance (target: <4s)
- ✅ Form validation (all required fields)
- ✅ Successful submission with database verification
- ✅ Different service categories testing
- ✅ Special characters and long text handling
- ✅ Performance under load (target: <5s average)
- ✅ Accessibility compliance
- ✅ Mobile responsiveness
- ✅ File upload functionality (artwork)
- ✅ Error handling and form reset
- ✅ CSRF protection verification
- ✅ Rate limiting functionality
- ✅ Minimum required fields testing

### **Performance Tests** (`FormPerformanceTest.php`)
- ✅ Concurrent form performance (5 submissions each)
- ✅ Database write performance (10 submissions)
- ✅ Validation performance (target: <2s contact, <2.5s quote)
- ✅ Memory usage monitoring (target: <50MB average)
- ✅ Large data handling (extensive text inputs)
- ✅ Error recovery performance (target: <8s contact, <10s quote)

## 🚀 **Quick Start**

### **Prerequisites**
```bash
# Install ChromeDriver (if not already installed)
brew install chromedriver  # macOS
# or
sudo apt-get install chromium-chromedriver  # Ubuntu

# Install Laravel Dusk
composer require --dev laravel/dusk
php artisan dusk:install
```

### **Run All Tests**
```bash
./tests/run-form-tests.sh
```

### **Run Specific Test Suites**
```bash
# Contact form tests only
./tests/run-form-tests.sh contact

# Quote form tests only
./tests/run-form-tests.sh quote

# Performance tests only
./tests/run-form-tests.sh performance
```

### **Run Individual Tests**
```bash
# Run specific test methods
php artisan dusk --filter="test_contact_form_successful_submission"
php artisan dusk --filter="test_quote_form_performance"
php artisan dusk --filter="test_forms_concurrent_performance"
```

## 📊 **Performance Benchmarks**

### **Page Load Times**
- **Contact Page**: < 3.0 seconds
- **Quote Page**: < 4.0 seconds

### **Form Submission Times**
- **Contact Form**: < 5.0 seconds
- **Quote Form**: < 6.0 seconds
- **Average Contact**: < 4.0 seconds
- **Average Quote**: < 5.0 seconds

### **Validation Performance**
- **Contact Validation**: < 2.0 seconds
- **Quote Validation**: < 2.5 seconds

### **Error Recovery**
- **Contact Error Recovery**: < 8.0 seconds
- **Quote Error Recovery**: < 10.0 seconds

### **Memory Usage**
- **Average Memory**: < 50MB per submission

## 🔧 **Test Configuration**

### **Environment Setup**
```bash
# Test environment variables
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### **Browser Configuration**
- **Browser**: Chrome (headless)
- **Window Size**: 1920x1080
- **Mobile Test**: 375x667 (iPhone SE)
- **ChromeDriver Port**: 9515

### **Test Data**
- **Contact Messages**: Automatically cleaned between tests
- **Quote Requests**: Automatically cleaned between tests
- **Test Products**: Created automatically for quote form testing

## 📝 **Test Reports**

### **Logging**
All tests log detailed performance metrics:
```php
Log::info('Contact form submission test completed', [
    'submission_time' => $submissionTime,
    'contact_message_id' => $contactMessageId
]);
```

### **Database Verification**
Tests verify database records are created correctly:
```php
$this->assertDatabaseHas('contact_messages', [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'status' => 'new'
]);
```

### **Performance Assertions**
```php
$this->assertLessThan(5.0, $submissionTime, "Contact form submission took too long: {$submissionTime}s");
```

## 🛠️ **Troubleshooting**

### **Common Issues**

#### **ChromeDriver Not Running**
```bash
# Start ChromeDriver manually
chromedriver --port=9515 &

# Or use the test script which handles this automatically
./tests/run-form-tests.sh
```

#### **Database Connection Issues**
```bash
# Ensure SQLite is available
php artisan migrate:fresh --env=testing
```

#### **Memory Issues**
```bash
# Increase PHP memory limit for large tests
php -d memory_limit=512M artisan dusk
```

#### **Timeout Issues**
```bash
# Increase timeout for slow environments
php artisan dusk --timeout=60
```

### **Debug Mode**
```bash
# Run tests with visible browser (non-headless)
DUSK_HEADLESS_DISABLED=1 php artisan dusk
```

## 📈 **Continuous Integration**

### **GitHub Actions Example**
```yaml
name: Form Performance Tests
on: [push, pull_request]

jobs:
  dusk-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install
      - name: Install ChromeDriver
        run: |
          sudo apt-get update
          sudo apt-get install -y chromium-chromedriver
      - name: Run Tests
        run: ./tests/run-form-tests.sh
```

## 🎯 **Test URLs**

### **Production URLs**
- **Contact Form**: https://www.melbourneprinthub.com.au/contact
- **Quote Form**: https://www.melbourneprinthub.com.au/get-quote

### **Local Development**
- **Contact Form**: http://localhost/contact
- **Quote Form**: http://localhost/get-quote

## 📋 **Test Checklist**

### **Before Running Tests**
- [ ] ChromeDriver is installed and running
- [ ] Laravel Dusk is installed
- [ ] Test database is configured
- [ ] Application is running locally
- [ ] All dependencies are installed

### **After Running Tests**
- [ ] All tests pass
- [ ] Performance benchmarks are met
- [ ] Database records are created correctly
- [ ] Error handling works as expected
- [ ] Mobile responsiveness is verified

## 🔍 **Monitoring & Alerts**

### **Performance Monitoring**
- Monitor test execution times
- Track memory usage trends
- Alert on performance degradation
- Log performance metrics for analysis

### **Quality Gates**
- All tests must pass
- Performance benchmarks must be met
- No critical accessibility issues
- Mobile responsiveness verified

## 📚 **Additional Resources**

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [ChromeDriver Setup](https://chromedriver.chromium.org/)
- [Melbourne Print Hub Website](https://www.melbourneprinthub.com.au)

---

**Last Updated**: September 2, 2025  
**Test Suite Version**: 1.0.0  
**Target Performance**: Production-ready forms with <5s submission times
