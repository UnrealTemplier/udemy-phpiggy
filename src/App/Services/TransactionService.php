<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db) {}

    public function create(array $formData): void
    {
        $formattedDate = "{$formData["date"]} 00:00:00";

        $this->db->query(
            "INSERT INTO transactions(user_id, description, amount, date) 
             VALUES(:user_id, :description, :amount, :date)",
            [
                "user_id" => $_SESSION["user"],
                "description" => $formData["description"],
                "amount" => $formData["amount"],
                "date" => $formattedDate,
            ],
        );
    }

    public function getUserTransactions(int $limit, int $offset): array
    {
        $searchTerm = addcslashes($_GET["s"] ?? "", "%_");
        $params = [
            "user_id" => $_SESSION["user"],
            "description" => "%{$searchTerm}%",
        ];

        $transactions = $this->db->query(
            "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date
             FROM transactions 
             WHERE user_id = :user_id
             AND description LIKE :description
             LIMIT $limit OFFSET $offset",
            $params,
        )->findAll();

        $transactionCount = $this->db->query(
            "SELECT COUNT(*)
             FROM transactions 
             WHERE user_id = :user_id
             AND description LIKE :description",
            $params,
        )->count();

        return [$transactions, $transactionCount];
    }

    public function getUserTransaction(int $id): mixed
    {
        return $this->db->query(
            "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date
             FROM transactions
             WHERE user_id = :user_id AND id = :id",
            [
                "user_id" => $_SESSION["user"],
                "id" => $id,
            ],
        )->find();
    }

    public function update(int $id, array $formData): void
    {
        $formattedDate = "{$formData["date"]} 00:00:00";

        $this->db->query(
            "UPDATE transactions
             SET description = :description, 
                 amount = :amount, 
                 date = :date
             WHERE user_id = :user_id AND id = :id",
            [
                "user_id" => $_SESSION["user"],
                "id" => $id,
                "description" => $formData["description"],
                "amount" => $formData["amount"],
                "date" => $formattedDate,
            ],
        );
    }

    public function delete(int $id): void
    {
        $this->db->query(
            "DELETE FROM transactions 
             WHERE user_id = :user_id AND id = :id",
            [
                "user_id" => $_SESSION["user"],
                "id" => $id,
            ],
        );
    }
}