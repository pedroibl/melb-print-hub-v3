<?php
/**
 * Database Connection Test for Melbourne Print Hub
 * Tests connection to JawsDB MySQL on Heroku
 */

// Database connection parameters from Heroku
$host = 'e11wl4mksauxgu1w.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$port = '3306';
$dbname = 'pial0xlk7wore30t';
$username = 'vetdn5o1nme97t9h';
$password = 'jrm36gzr1gxqifkr';

echo "<h1>Database Connection Test</h1>";
echo "<h2>Melbourne Print Hub - Production</h2>";

try {
    // Create PDO connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<div style='color: green; font-weight: bold;'>✅ Database connection successful!</div>";
    
    // Test basic queries
    echo "<h3>Database Information:</h3>";
    
    // Get database version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "<p><strong>MySQL Version:</strong> " . $version['version'] . "</p>";
    
    // Get database name
    $stmt = $pdo->query("SELECT DATABASE() as dbname");
    $dbname = $stmt->fetch();
    echo "<p><strong>Current Database:</strong> " . $dbname['dbname'] . "</p>";
    
    // List all tables
    echo "<h3>Tables in Database:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "<li>$tableName</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tables found in database.</p>";
    }
    
    // Test specific tables if they exist
    echo "<h3>Table Details:</h3>";
    
    $testTables = ['products', 'quote_requests', 'contact_messages', 'users', 'sessions'];
    
    foreach ($testTables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch();
            echo "<p><strong>$table:</strong> " . $result['count'] . " records</p>";
        } catch (PDOException $e) {
            echo "<p><strong>$table:</strong> <span style='color: red;'>Table not found</span></p>";
        }
    }
    
    // Test a sample query from products table
    try {
        $stmt = $pdo->query("SELECT name, category, base_price FROM products LIMIT 5");
        $products = $stmt->fetchAll();
        
        if (count($products) > 0) {
            echo "<h3>Sample Products:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Name</th><th>Category</th><th>Base Price</th></tr>";
            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                echo "<td>" . htmlspecialchars($product['category']) . "</td>";
                echo "<td>$" . htmlspecialchars($product['base_price']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (PDOException $e) {
        echo "<p><span style='color: red;'>Error querying products: " . $e->getMessage() . "</span></p>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Database connection failed!</div>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
}

echo "<hr>";
echo "<p><strong>Test completed at:</strong> " . date('Y-m-d H:i:s T') . "</p>";
echo "<p><a href='/phpmyadmin'>Go to phpMyAdmin</a></p>";
?>
