<?php
$host = "db";
$dbname = "phpdb";
$username = "root";
$password = "test";

// Create a connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // SQL statement to create the table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS names (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    )";

    // Execute the query
    $pdo->exec($sql);
    echo "Table 'names' created successfully!";
} catch (PDOException $e) {
    die("Database connection or table creation failed: " . $e->getMessage());
}