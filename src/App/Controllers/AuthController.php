<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserService;
use App\Services\FormValidationService;
use Framework\TemplateEngine;

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private FormValidationService $formValidationService,
        private UserService $userService,
    ) {}

    public function registerView()
    {
        echo $this->view->render("register.php", [
            "title" => "Register",
        ]);
    }

    public function register()
    {
        $this->formValidationService->validateRegister($_POST);
        $this->userService->checkEmailTaken($_POST["email"]);
        redirectTo("/");
    }
}
