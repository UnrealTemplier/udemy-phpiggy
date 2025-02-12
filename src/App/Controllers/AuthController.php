<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{FormValidationService, UserService};
use Framework\TemplateEngine;

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private FormValidationService $formValidationService,
        private UserService $userService,
    ) {
    }

    public function registerView(): void
    {
        echo $this->view->render("register.php", [
            "title" => "Register",
        ]);
    }

    public function register(): void
    {
        $this->formValidationService->validateRegister($_POST);
        $this->userService->checkEmailTaken($_POST["email"]);
        $this->userService->create($_POST);
        redirectTo("/");
    }

    public function loginView(): void
    {
        echo $this->view->render("login.php", [
            "title" => "Login",
        ]);
    }

    public function login(): void
    {
        $this->formValidationService->validateLogin($_POST);
        $this->userService->login($_POST);
        redirectTo("/");
    }

    public function logout(): void
    {
        $this->userService->logout();
        redirectTo("/login");
    }
}
