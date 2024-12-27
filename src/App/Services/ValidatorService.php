<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\{RequiredRule, EmailRule, InRule, MinRule};
use Framework\FormValidator;

class ValidatorService
{
    private FormValidator $validator;

    public function __construct()
    {
        $this->validator = new FormValidator();
        $this->validator->addRule("required", new RequiredRule());
        $this->validator->addRule("email", new EmailRule());
        $this->validator->addRule("min", new MinRule());
        $this->validator->addRule("in", new InRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            "email"             => ["required", "email"],
            "age"               => ["required", "min:18"],
            "country"           => ["required", "in:USA,Canada,Mexico"],
            "socialMediaURL"    => ["required"],
            "password"          => ["required"],
            "confirmPassword"   => ["required"],
            "tos"               => ["required"],
        ]);
    }
}
