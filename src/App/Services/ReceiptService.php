<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class ReceiptService
{
    public function __construct(private Database $db) {}

    public function validateFile(?array $fileArray): void
    {
        if (!$fileArray || $fileArray["error"] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                "receipt" => ["Failed to upload file"],
            ]);
        }

        if ($fileArray["size"] === 0) {
            throw new ValidationException([
                "receipt" => ["File is empty"],
            ]);
        }

        $maxFileSizeInMb = 3;
        $maxFileSizeInBytes = $maxFileSizeInMb * 1024 * 1024;
        if ($fileArray["size"] > $maxFileSizeInBytes) {
            throw new ValidationException([
                "receipt" => ["File is too large"],
            ]);
        }

        $originalFileName = $fileArray["name"];
        if (!preg_match("/^[a-zA-Z0-9\s._-]+$/", $originalFileName)) {
            throw new ValidationException([
                "receipt" => ["Invalid file name"],
            ]);
        }

        $originalMimeType = $fileArray["type"];
        $allowedMimeTypes = ["image/jpeg", "image/png", "application/pdf"];
        if (!in_array($originalMimeType, $allowedMimeTypes)) {
            throw new ValidationException([
                "receipt" => ["Invalid file type"],
            ]);
        }
    }

    public function upload(array $fileArray, int $transactionId): void
    {
        $fileExtension = strtolower(pathinfo($fileArray["name"], PATHINFO_EXTENSION));
        $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFileName;

        if (!move_uploaded_file($fileArray["tmp_name"], $uploadPath)) {
            throw new ValidationException([
                "receipt" => ["Failed to upload file"],
            ]);
        }

        $this->db->query(
            "INSERT INTO receipts(
                transaction_id, original_filename, storage_filename, media_type)
            VALUES (:transaction_id, :original_filename, :storage_filename, :media_type)",
            [
                "transaction_id" => $transactionId,
                "original_filename" => $fileArray["name"],
                "storage_filename" => $newFileName,
                "media_type" => $fileArray["type"],
            ],
        );
    }

    public function getReceipt(int $id): mixed
    {
        return $this->db->query(
            "SELECT * FROM receipts WHERE id = :id",
            ["id" => $id],
        )->find();
    }

    public function read(array $receipt): void
    {
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt["storage_filename"];
        if (!file_exists($filePath)) {
            redirectTo("/");
        }

        header("Content-Disposition: inline; filename={$receipt["original_filename"]}");
        header("Content-Type: " . $receipt["media_type"]);

        readfile($filePath);
    }

    public function delete(array $receipt): void
    {
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt["storage_filename"];
        if (!file_exists($filePath)) {
            redirectTo("/");
        }

        unlink($filePath);

        $this->db->query(
            "DELETE FROM receipts WHERE id = :id",
            ["id" => $receipt["id"]],
        );
    }
}