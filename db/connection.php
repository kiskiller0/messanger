<?php
require_once "../php/functions.php";
$connection = new mysqli("localhost", "root", "", "messanger");

if (!$connection)
	die("error connecting to db:\n" . $connection->connect_errno);

class Table
{
	private $connection;
	private $name;
	private $fields;
	private $requiredFields;
	private $uniqueValues;

	public function __construct($connection, $name, $fields)
	{
		$this->name = $name;
		$this->connection = $connection;
		$this->fields = $fields;
	}

	function getWhere($what, $column, $value)
	{
		// sanitizing the received value to prepare it for output
		$value = sanitize($value, $this->connection);
		// sql query preparation
		$sql = "SELECT $what FROM " . $this->name . " WHERE " . $column . " = \"" . $value . "\";";

		$query = mysqli_query($this->connection, $sql);
		$results = [];
		while ($row = mysqli_fetch_assoc($query)) {
			$results[] = $row;
		}
		if (count($results) == 1)
			$results = $results[0];
		return $results;
	}

	function addNew($fields)
	{
		// 1. checking if all required fields are present:
		if (!$this->allRequiredFieldsPresent($fields)) {
			return false;
		}

		// 2. filtering out non-needed values from supposedly the $_POST array:
		$neededFields = [];
		foreach ($fields as $key => $value) {
			if (in_array($key, $this->fields)) {
				$neededFields  += [$key => $value];
			}
		}
		$fields = $neededFields;
		$fields = $this->filterByKeys($this->fields, $fields, true);

		// 3. preparing the sql query:
		$keys = [];
		$values = [];
		foreach ($fields as $key => $value) {
			// 4. not forgetting to sanitize the received values meanwhile:
			$keys[] = $key;
			$values[] = mysqli_real_escape_string($this->connection, htmlentities($value));
		}

		$sql = "INSERT INTO " . $this->name . "(" . implode(',', $keys) . ") VALUES(" . implode(",", array_map(function ($ele) {
			return "\"$ele\"";
		}, $values)) . ");";

		// echo $sql;
		try {
			return mysqli_query($this->connection, $sql);
		} catch (Exception $err) {
			return false;
		}
		// return mysqli_query($this->connection, "SDASFASDF");
	}

	function setRequiredFields(array $fields)
	{
		$this->requiredFields = $fields;
	}

	function setUniqueFields($fields)
	{
		$this->uniqueValues = $fields;
	}

	function exists($fields)
	{
		$existingFields = [];
		foreach ($fields as $key => $value) {
			if (in_array($key, $this->uniqueValues)) {
				$sql = "SELECT $key FROM $this->name WHERE $key = \"$value\";";
				$query = mysqli_query($this->connection, $sql);
				if (mysqli_fetch_row($query)) {
					$existingFields[] = $key;
				}
			}
		}
		return $existingFields;
	}

	function allRequiredFieldsPresent($fields)
	{
		foreach ($this->requiredFields as $key) {
			if (!in_array($key, array_keys($fields))) {
				echo "$key is not in";
				print_r($fields);
				return false;
			}
		}
		return true;
	}

	function filterByKeys($keys, $assocArray, $fillMissing = false)
	{
		$filteredArray = [];
		foreach ($keys as $key) {
			if (!in_array($key, $assocArray)) {
				if ($fillMissing) {
					$filteredArray += [$key => null];
					continue;
				}
				return false;
			}
			$filteredArray += [$key => $assocArray[$key]];
		}
		return $filteredArray;
	}
}

class User extends Table
{

	public function __construct($connection, $name, $fields)
	{
		parent::__construct($connection, $name, $fields);
	}
}

$USER = new User($connection, 'user', ['username', 'password', 'email', 'bio', 'picture']);
$USER->setRequiredFields(['username', 'password', 'email']);
$USER->setUniqueFields(['username', 'email']);
