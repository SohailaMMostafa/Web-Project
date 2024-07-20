<?php

class DBOps
{
    private $server_name, $username, $password, $db_name;
    public mysqli $connection;

    function __construct($server_name, $username, $password, $db_name)
    {
        $this->server_name = $server_name;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;

        $this->connection = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);
        if ($this->connection->connect_error) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $this->connection;
    }

    public function select_query($table_name,$row_name,$value = NULL)
    {
        $sql = "SELECT `$row_name` FROM `$table_name`";
        if ($value != NULL){
            $sql = "SELECT `$row_name` FROM `$table_name` WHERE $row_name = '$value'";
        }
        try {
            $query = mysqli_query($this->connection, $sql);
            $row = mysqli_fetch_array($query);
            if ($row === null) {
                // Handle case where no rows are returned
                return array(
                    "status_code" => 404,
                    "body" => ["No data found"]
                );
            }
            return array(
                "status_code" => 200,
                'body' => $row
            );

        } catch (Exception $e) {
            return array(
                "status_code" => 500,
                "body" => [$e->getMessage()]
            );
        }
    }

    public function insert_query($table_name, $columns, $values)
    {
        $sql = "INSERT INTO $table_name (";
        foreach ($columns as $col) {
            $sql .= " $col,";
        }
        $sql = rtrim($sql, ',');
        $sql .= ') VALUES (';
        foreach ($values as $value) {
            $sql .= " '$value',";
        }
        $sql = rtrim($sql, ',');
        $sql .= ')';

        try {
            $query = mysqli_query($this->connection, $sql);
            return array(
                'status_code' => 200,
                'message' => "New record created successfully",
                'body' => ""
            );

        } catch (Exception $e) {
            return array(
                "status_code" => 500,
                'message' => $e->getMessage(),
                "body" => mysqli_errno($this->connection)
            );
        }
    }

    public function close_connection(): void
    {
        mysqli_close($this->connection);
    }
}