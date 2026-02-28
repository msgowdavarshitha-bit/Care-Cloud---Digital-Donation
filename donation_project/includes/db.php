<?php
// MongoDB Connection
require_once __DIR__ . '/vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $database = $client->donation_db;
    
    $users_collection = $database->users;
    $donations_collection = $database->donations;
    $suggestions_collection = $database->suggestions;
} catch(Exception $e) {
    die("MongoDB Connection failed: " . $e->getMessage());
}
?>