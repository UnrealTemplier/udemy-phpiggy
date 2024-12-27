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

try {
    $db->connection->beginTransaction();

    $db->connection->query("INSERT INTO products VALUES(99, 'Gloves');");
    // $db->connection->query("DELETE FROM products WHERE id=99");

    $where = "Gloves";
    $query = "SELECT * FROM products WHERE name=:name";
    $stmt = $db->connection->prepare($query);
    $stmt->bindValue("name", $where, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));

    $db->connection->commit();
} catch (Exception $e) {
    $db->connection->rollBack();
    echo "Transation failed.";
}
