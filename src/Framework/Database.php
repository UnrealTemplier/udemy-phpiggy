<?php

declare(strict_types=1);

namespace Framework;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public PDO $connection;
    private PDOStatement $stmt;

    public function __construct($driver, $config, $username, $password)
    {
        $config = http_build_query(data: $config, arg_separator: ";");
        $dsn = "{$driver}:{$config}";

        try {
            $this->connection = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die("Unable to connect to the database.");
        }
    }

    public function query(string $query, array $params = [])
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($params);
    }
}
