<?php

declare(strict_types=1);

namespace App\Services;

use Framework\FormValidator;
use Framework\Rules\{EmailRule, InRule, MatchRule, MinRule, RequiredRule, UrlRule};

class FormValidationService
{
    private FormValidator $formValidator;

    public function __construct()
    {
        $this->formValidator = new FormValidator();
        $this->formValidator->addRule("required", new RequiredRule());
        $this->formValidator->addRule("email", new EmailRule());
        $this->formValidator->addRule("min", new MinRule());
        $this->formValidator->addRule("in", new InRule());
        $this->formValidator->addRule("url", new UrlRule());
        $this->formValidator->addRule("match", new MatchRule());
    }

    public function validateRegister(array $formData): void
    {
        $this->formValidator->validate($formData, [
            "email" => ["required", "email"],
            "age" => ["required", "min:18"],
            "country" => ["required", "in:USA,Canada,Mexico"],
            "socialMediaUrl" => ["required", "url"],
            "password" => ["required"],
            "confirmPassword" => ["required", "match:password"],
            "tos" => ["required"],
        ]);
    }

    public function validateLogin(array $formData): void
    {
        $this->formValidator->validate($formData, [
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);
    }

    public function validateTransaction(array $formData): void
    {
        $this->formValidator->validate($formData, [
            "description" => ["required"],
            "amount" => ["required"],
            "date" => ["required"],
        ]);
    }
}
