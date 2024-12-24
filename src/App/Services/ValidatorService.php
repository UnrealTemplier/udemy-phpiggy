<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\RequiredRule;
use Framework\FormValidator;

class ValidatorService
{
    private FormValidator $validator;

    public function __construct()
    {
        $this->validator = new FormValidator();
        $this->validator->addRule("required", new RequiredRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            "email"             => ["required"],
            "age"               => ["required"],
            "country"           => ["required"],
            "socialMediaURL"    => ["required"],
            "password"          => ["required"],
            "confirmPassword"   => ["required"],
            "tos"               => ["required"],
        ]);
    }
}
