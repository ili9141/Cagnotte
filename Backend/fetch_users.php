<?php
// Include your database connection file
require_once 'db.php';

// Create a connection to the database
$db = (new Database())->getConnection();

// Query to fetch all users
$query = "SELECT id, name, email, type, created_at FROM users";
$stmt = $db->prepare($query);
$stmt->execute();

// Fetch all users
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
