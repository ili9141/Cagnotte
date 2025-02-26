<?php
class User
{
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $type;
    public $remember_token;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        if (!$this->isPasswordStrong($this->password)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " 
                 (name, email, password, type, remember_token) 
                 VALUES 
                 (:name, :email, :password, :type, :remember_token)";

        $stmt = $this->conn->prepare($query);

        $this->remember_token = bin2hex(random_bytes(32));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':remember_token', $this->remember_token);

        return $stmt->execute();
    }

    public function getUserByEmail($email)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return null;
        }
    }

    public function getUserById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return false;
        }
    }

    public function login($remember = false)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->type = $row['type'];

            $_SESSION['user_id'] = $this->id;
            $_SESSION['user_name'] = $this->name;
            $_SESSION['user_type'] = $this->type;


            if ($remember) {
                $this->remember_token = bin2hex(random_bytes(32));
                $this->updateRememberToken();
                setcookie('remember_token', $this->remember_token, time() + (86400 * 30), '/');
                setcookie('user_id', $this->id, time() + (86400 * 30), '/');
            }

            return true;
        }
        return false;
    }

    private function updateRememberToken()
    {
        $query = "UPDATE " . $this->table . " 
                 SET remember_token = :token 
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $this->remember_token);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function loginWithRememberToken($user_id, $token)
    {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE id = :id AND remember_token = :token 
                 LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->type = $row['type'];
            return true;
        }
        return false;
    }

    // Changed the visibility to public
    public function isPasswordStrong($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            return false;
        }

        return true;
    }

    public function logout()
    {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
        }

        $this->remember_token = null;
        $this->updateRememberToken();
        session_destroy();
    }

    

    // Add the edit method
    public function update()
{
    $query = "UPDATE " . $this->table . " 
              SET name = :name, email = :email" . 
              ($this->password ? ", password = :password" : "") . " 
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':id', $this->id);

    if ($this->password) {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
    }

    return $stmt->execute();
}




    // Add the delete method
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }





}
