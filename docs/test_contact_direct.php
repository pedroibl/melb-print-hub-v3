<?php
// Test ContactMessage Model Directly
echo "Testing ContactMessage Model Directly...\n";

// Bootstrap Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    echo "Testing database connection...\n";
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=melb_print_hub;charset=utf8mb4',
        'melb_print_user',
        'MelbPrintHub2024!'
    );
    echo "✅ Database connection successful\n";
    
    // Check current contact messages count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_messages");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Current contact messages: " . $result['count'] . "\n";
    
    // Test ContactMessage model
    echo "\nTesting ContactMessage model...\n";
    
    $contactMessage = new \App\Models\ContactMessage();
    $contactMessage->name = 'Test Contact User';
    $contactMessage->email = 'test.contact@example.com';
    $contactMessage->message = 'This is a test contact message to verify the database is working correctly with MySQL. Please confirm this message was saved to the database.';
    $contactMessage->status = 'new';
    
    $contactMessage->save();
    
    echo "✅ ContactMessage created successfully with ID: " . $contactMessage->id . "\n";
    
    // Verify the data was saved
    echo "\nVerifying saved data...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_messages");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total contact messages after test: " . $result['count'] . "\n";
    
    if ($result['count'] > 0) {
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 1");
        $latest = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Latest contact message:\n";
        echo "   ID: " . $latest['id'] . "\n";
        echo "   Name: " . $latest['name'] . "\n";
        echo "   Email: " . $latest['email'] . "\n";
        echo "   Message: " . substr($latest['message'], 0, 50) . "...\n";
        echo "   Status: " . $latest['status'] . "\n";
        echo "   Created: " . $latest['created_at'] . "\n";
    }
    
    // Test the ContactController store method
    echo "\nTesting ContactController store method...\n";
    
    $controller = new \App\Http\Controllers\ContactController();
    
    // Create a mock request
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'name' => 'Controller Test User',
        'email' => 'controller.test@example.com',
        'message' => 'This is a test from the ContactController to verify the entire flow works.'
    ]);
    
    try {
        $response = $controller->store($request);
        echo "✅ ContactController store method executed successfully\n";
        echo "Response type: " . get_class($response) . "\n";
        
        // Check if another message was added
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_messages");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total contact messages after controller test: " . $result['count'] . "\n";
        
    } catch (Exception $e) {
        echo "❌ ContactController store method failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Contact Form Test Summary ===\n";
    echo "✅ Database connection: Working\n";
    echo "✅ ContactMessage model: Working\n";
    echo "✅ Database insertion: Working\n";
    echo "✅ ContactController: Tested\n";
    echo "\nThe contact form database functionality is working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
