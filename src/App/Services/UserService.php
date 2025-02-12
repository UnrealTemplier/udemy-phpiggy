<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db)
    {
    }

    /**
     * Checks if provided email is already taken.
     *
     * @param string $email Email address to check.
     *
     * @return void
     *
     * @throws ValidationException Throws an exception if email is already taken.
     */
    public function checkEmailTaken(string $email): void
    {
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email=:email",
            [
                "email" => $email,
            ],
        )->count();

        if ($emailCount > 0) {
            throw new ValidationException(["email" => ["Email is already taken."]]);
        }
    }

    public function create(array $formData): void
    {
        $password = password_hash($formData["password"], PASSWORD_BCRYPT, ["cost" => 12]);

        $this->db->query(
            "INSERT INTO users(email, password, age, country, social_media_url)
            VALUES(:email, :password, :age, :country, :socialMediaUrl);",
            [
                "email" => $formData["email"],
                "password" => $password,
                "age" => $formData["age"],
                "country" => $formData["country"],
                "socialMediaUrl" => $formData["socialMediaUrl"],
            ],
        );
    }

    public function login(array $formData): void
    {
        $user = $this->db->query("SELECT * FROM users WHERE email=:email", [
            "email" => $formData["email"],
        ])->find();

        $passwordsMatch = password_verify($formData["password"], $user["password"] ?? "");

        if (!$user || !$passwordsMatch) {
            throw new ValidationException(["password" => ["Invalid credentials."]]);
        }

        session_regenerate_id();

        $_SESSION["user"] = $user["id"];
    }

    public function logout(): void
    {
        unset($_SESSION["user"]);
        session_regenerate_id(true);
    }
}
