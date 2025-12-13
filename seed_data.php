<?php
// Database seeding script
define('BASEPATH', __DIR__ . '/system/');

// Load database configuration
require_once __DIR__ . '/application/config/database.php';

// Create connection
$db = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Disable foreign key checks temporarily
$db->query("SET FOREIGN_KEY_CHECKS = 0");

// Read and execute demo data
$demo_file = __DIR__ . '/database/demo_products.sql';

if (!file_exists($demo_file)) {
    die("Demo data file not found: $demo_file");
}

$sql = file_get_contents($demo_file);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));

$errors = [];
$success_count = 0;

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (!$db->query($statement)) {
            $errors[] = "Error executing query: " . $db->error . "\nQuery: " . substr($statement, 0, 100) . "...";
        } else {
            $success_count++;
            echo "✓ Executed query\n";
        }
    }
}

// Re-enable foreign key checks
$db->query("SET FOREIGN_KEY_CHECKS = 1");

if (!empty($errors)) {
    echo "Errors occurred:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
} else {
    echo "✅ Demo data seeded successfully! Executed $success_count statements.\n";
    echo "\nData loaded:\n";
    echo "  ✓ 22 Ingredients\n";
    echo "  ✓ 10 Meals\n";
    echo "  ✓ 45 Meal Recipes\n";
}

$db->close();
?>
