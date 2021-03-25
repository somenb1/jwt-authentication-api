<?php
class Notes
{
    private $conn;
    private $table;

    public $user_id;
    public $heading;
    public $description;
    public $type;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->table = 'notes';
    }
    function create()
    {
        $query = "INSERT INTO " . $this->table . " (user_id, heading, description, type) VALUES ('" . $this->user_id . "', '" . $this->heading . "', '" . $this->description . "' , '" . $this->type . "')";
        if ($this->conn->query($query)) {
            return true;
        }
        return false;
    }
    function get_my_notes()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = '" . $this->user_id . "'";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            return  $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    function get_public_notes()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE type = 'public'";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            return  $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }
}
