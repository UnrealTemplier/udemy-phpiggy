<?php

declare(strict_types=1);

namespace Framework;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private PDO $connection;
    private PDOStatement $stmt;

    public function __construct($driver, $config, $username, $password)
    {
        $config = http_build_query(data: $config, arg_separator: ";");
        $dsn = "{$driver}:{$config}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Unable to connect to the database.");
        }
    }

    public function query(string $query, array $params = []): Database
    {
        $this->stmt = $this->connection->prepare($query);
        $this->stmt->execute($params);
        return $this;
    }

    public function count(): mixed
    {
        return $this->stmt->fetchColumn();
    }

    public function find(): mixed
    {
        return $this->stmt->fetch();
    }

    public function findAll(): mixed
    {
        return $this->stmt->fetchAll();
    }

    public function id(): false|string
    {
        return $this->connection->lastInsertId();
    }
}
