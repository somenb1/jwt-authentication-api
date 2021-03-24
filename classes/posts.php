<?php
class User
{
    private $conn;

    public $posts = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function get_all()
    {

        $query = "SELECT * FROM posts WHERE 1";

        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                array_push($posts, array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'created_on' => $row['created_on']
                ));
            }
            return true;
        }
        return false;
    }
}
