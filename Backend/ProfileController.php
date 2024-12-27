<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it's not started already
}
require_once 'db.php'; // Include your database connection
require_once 'User.php'; // Include the User class

class ProfileController {
    private $user;

    public function __construct() {
        // Initialize the database connection
        $db = new Database(); // Assuming `Database` is the class in db.php
        $conn = $db->getConnection();

        // Initialize the User class
        $this->user = new User($conn);
    }

    // Display the user's profile
    public function viewProfile() {
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            return ["error" => "User not logged in."];
        }

        $userId = $_SESSION['user_id']; // Get the logged-in user's ID
        $userData = $this->user->getUserById($userId);

        if ($userData) {
            return $userData;
        } else {
            return ["error" => "User not found."];
        }
    }

    // Update the user's profile
    
            public function updateProfile($name, $email, $password = null) {
            // Get the user ID from session or another source
            $userId = $_SESSION['user_id'];  // Assuming the user is logged in
            
            // Initialize the database connection
            $db = new Database(); // Assuming you have a Database class for DB interaction
            $conn = $db->getConnection();
    
            // Start the SQL query to update the user
            $query = "UPDATE users SET name = :name, email = :email";
    
            // Add password update condition if a new password is provided
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $query .= ", password = :password";
            }
    
            // Complete the query with the WHERE clause
            $query .= " WHERE id = :id";
    
            $stmt = $conn->prepare($query);
    
            // Bind the parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $userId);
            
            // If there's a new password, bind the password parameter
            if (!empty($password)) {
                $stmt->bindParam(':password', $passwordHash);
            }
    
            // Execute the statement and return whether it was successful
            return $stmt->execute();
        }
    

    // Delete the user's profile
    public function deleteProfile() {
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            return ["error" => "User not logged in."];
        }

        $userId = $_SESSION['user_id']; // Get the logged-in user's ID
        $this->user->id = $userId;

        if ($this->user->delete()) {
            // Logout the user after deleting their account
            session_destroy();
            return ["success" => "Profile deleted successfully."];
        } else {
            return ["error" => "Failed to delete profile."];
        }
    }
}
?>
