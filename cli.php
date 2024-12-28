<?php

declare(strict_types=1);

use Framework\Database;

include __DIR__ . "/src/Framework/Database.php";

$driver = "mysql";
$config = [
    "host"      => "localhost",
    "port"      => 3306,
    "dbname"    => "phpiggy",
];
$username = "root";
$password = "";

$db = new Database($driver, $config, $username, $password);

$sqlFile = file_get_contents("./database.sql");
$db->connection->query($sqlFile);
