<?php
class User
{
    private $conn;
    private $table;

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->table = 'users';
    }

    function login()
    {

        $query = "SELECT id, name, email FROM " . $this->table . " WHERE email = '" . $this->email . "' AND password = '" . $this->password . "'  LIMIT 0, 1";

        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            return true;
        }

        return false;
    }
}
