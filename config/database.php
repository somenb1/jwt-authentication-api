<?php
class Database
{
    private $host = "localhost";
    private $db_name = "api_jwt";
    private $user = "root";
    private $password = "root";
    public $con = NULL;

    public function connect()
    {
        try {
            if ($this->con = mysqli_connect($this->host, $this->user, $this->password, $this->db_name)) {
                return $this->con;
            } else {
                throw new Exception("Unable to connect : " . mysqli_connect_error());
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
