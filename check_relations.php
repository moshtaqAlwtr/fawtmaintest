<?php
require_once 'vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if there are any client relations
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM client_relations");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total client relations: " . $result['count'] . "\n";
    
    if ($result['count'] > 0) {
        // Get a few sample relations
        $stmt = $pdo->query("SELECT id, client_id, description, process, employee_id, deposit_count, attachments FROM client_relations LIMIT 5");
        $relations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Sample relations:\n";
        foreach ($relations as $relation) {
            echo "ID: " . $relation['id'] . "\n";
            echo "Client ID: " . $relation['client_id'] . "\n";
            echo "Description: " . $relation['description'] . "\n";
            echo "Process: " . $relation['process'] . "\n";
            echo "Employee ID: " . $relation['employee_id'] . "\n";
            echo "Deposit Count: " . $relation['deposit_count'] . "\n";
            echo "Attachments: " . $relation['attachments'] . "\n";
            echo "-------------------\n";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}