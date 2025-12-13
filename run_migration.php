<?php
// Database migration runner
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

// Get the latest migration file (default to 004)
$migration_file = __DIR__ . '/database/migrations/004_drop_meal_recipes.sql';

// Check if a specific migration number is passed
if (isset($argv[1])) {
    $migration_file = __DIR__ . '/database/migrations/' . sprintf('%03d', $argv[1]) . '_*.sql';
    $files = glob($migration_file);
    if (empty($files)) {
        die("Migration file not found for number: " . $argv[1]);
    }
    $migration_file = $files[0];
}

if (!file_exists($migration_file)) {
    die("Migration file not found: $migration_file");
}

$sql = file_get_contents($migration_file);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));

$errors = [];
$success_count = 0;

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (!$db->query($statement)) {
            $errors[] = $db->error;
        } else {
            $success_count++;
        }
    }
}

if (!empty($errors)) {
    echo "Errors occurred:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
} else {
    echo "Migration completed successfully! Executed $success_count statements.\n";
}

$db->close();
?>
