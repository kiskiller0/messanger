<?php
$connection = new mysqli("localhost", "root", "", "messanger");

if (!$connection)
    die("error connecting to db:\n" . $connection->connect_errno);

class Table
{
    private $connection;
    private $name;
    private $fields;
    private $requiredFields;

    public function __construct($connection, $name, $fields)
    {
        $this->name = $name;
        $this->connection = $connection;
        $this->fields = $fields;
    }

    function getWhere($what, $column, $value)
    {
        $sql = "";
        // sanitizing the received value to prepare it for output
        $value = htmlentities($value);
        // sanitizing the received value to prepare it for storage in database
        $value = mysqli_real_escape_string($this->connection, $value);

        if ($what == '*') {
            $sql .= "*";
        } else {
            $sql .= "\"$what\"";
        }
        $results = [];
        $query = mysqli_query($this->connection, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $results[] = $row;
        }
        return $results;
    }

    function addNew($fields)
    {
        // 1. checking if all required fields are present:
        foreach ($this->requiredFields as $key) {
            if (!in_array($key, $fields))
                return false;
        }

        // 2. checking that nothing out of the defined fields is supplied:
        foreach ($fields as $field => $value) {
            if (!in_array($field, $this->fields)) {
                return false;
            }
        }

        // 3. building the sql query:

    }

    function setRequiredFields(array $fields)
    {
        $this->requiredFields = $fields;
    }
}

class User extends Table
{

    public function __construct($connection, $name, $fields)
    {
        parent::__construct($connection, $name, $fields);
    }
}

$USER = new User($connection, "user", ["username", "password", "email", "bio", "picture"]);
