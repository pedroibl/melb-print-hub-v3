#!/bin/bash

# Melbourne Print Hub - Form Performance Test Runner
# This script runs comprehensive Dusk tests for the contact and quote forms

echo "🚀 Melbourne Print Hub - Form Performance Test Suite"
echo "=================================================="
echo ""

# Set environment variables for testing
export APP_ENV=testing
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if ChromeDriver is running
check_chromedriver() {
    if ! pgrep -x "chromedriver" > /dev/null; then
        print_warning "ChromeDriver not running. Starting ChromeDriver..."
        chromedriver --port=9515 &
        sleep 2
    else
        print_success "ChromeDriver is running"
    fi
}

# Run a specific test
run_test() {
    local test_name=$1
    local test_file=$2
    
    print_status "Running $test_name..."
    
    start_time=$(date +%s)
    
    if php artisan dusk --filter="$test_name" 2>&1; then
        end_time=$(date +%s)
        duration=$((end_time - start_time))
        print_success "$test_name completed successfully in ${duration}s"
        return 0
    else
        print_error "$test_name failed"
        return 1
    fi
}

# Main test execution
main() {
    print_status "Starting form performance tests..."
    
    # Check prerequisites
    check_chromedriver
    
    # Create test database
    print_status "Setting up test database..."
    php artisan migrate:fresh --env=testing
    
    # Run individual test suites
    tests=(
        "ContactFormTest"
        "QuoteFormTest" 
        "FormPerformanceTest"
    )
    
    failed_tests=()
    total_tests=${#tests[@]}
    passed_tests=0
    
    for test in "${tests[@]}"; do
        if run_test "$test" "tests/Browser/${test}.php"; then
            ((passed_tests++))
        else
            failed_tests+=("$test")
        fi
        echo ""
    done
    
    # Summary
    echo "=================================================="
    echo "📊 Test Summary"
    echo "=================================================="
    echo "Total Tests: $total_tests"
    echo "Passed: $passed_tests"
    echo "Failed: $(($total_tests - $passed_tests))"
    
    if [ ${#failed_tests[@]} -eq 0 ]; then
        print_success "All tests passed! 🎉"
        exit 0
    else
        print_error "Some tests failed:"
        for test in "${failed_tests[@]}"; do
            echo "  - $test"
        done
        exit 1
    fi
}

# Run specific test if provided
if [ $# -eq 1 ]; then
    case $1 in
        "contact")
            run_test "ContactFormTest" "tests/Browser/ContactFormTest.php"
            ;;
        "quote")
            run_test "QuoteFormTest" "tests/Browser/QuoteFormTest.php"
            ;;
        "performance")
            run_test "FormPerformanceTest" "tests/Browser/FormPerformanceTest.php"
            ;;
        *)
            echo "Usage: $0 [contact|quote|performance]"
            echo "  contact     - Run only contact form tests"
            echo "  quote       - Run only quote form tests"
            echo "  performance - Run only performance tests"
            echo "  (no args)   - Run all tests"
            exit 1
            ;;
    esac
else
    main
fi
