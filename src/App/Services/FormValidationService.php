<?php

declare(strict_types=1);

namespace App\Services;

use Framework\FormValidator;
use Framework\Rules\{EmailRule, InRule, LengthMaxRule, MatchRule, NumericRule, RequiredRule, UrlRule, ValueMinRule};

class FormValidationService
{
    private FormValidator $formValidator;

    public function __construct()
    {
        $this->formValidator = new FormValidator();
        $this->formValidator->addRule("required", new RequiredRule());
        $this->formValidator->addRule("email", new EmailRule());
        $this->formValidator->addRule("min", new ValueMinRule());
        $this->formValidator->addRule("in", new InRule());
        $this->formValidator->addRule("url", new UrlRule());
        $this->formValidator->addRule("match", new MatchRule());
        $this->formValidator->addRule("lengthMax", new LengthMaxRule());
        $this->formValidator->addRule("numeric", new NumericRule());
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
            "description" => ["required", "lengthMax:255"],
            "amount" => ["required", "numeric"],
            "date" => ["required"],
        ]);
    }
}
