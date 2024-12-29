<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db) {}

    /**
     * Checks if provided email is already taken.
     *
     * @param string $email Email address to check.
     *
     * @return void
     *
     * @throws ValidationException Throws an exception if email is already taken.
     */
    public function checkEmailTaken(string $email)
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

    public function create(array $formData)
    {
        $this->db->query(
            "INSERT INTO users(email, password, age, country, social_media_url)
            VALUES(:email, :password, :age, :country, :socialMediaUrl);",
            [
                "email"             => $formData["email"],
                "password"          => $formData["password"],
                "age"               => $formData["age"],
                "country"           => $formData["country"],
                "socialMediaUrl"    => $formData["socialMediaUrl"],
            ],
        );
    }
}
