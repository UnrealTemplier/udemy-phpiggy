<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{FormValidationService, TransactionService};
use Framework\TemplateEngine;

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private FormValidationService $formValidationService,
        private TransactionService $transactionService,
    ) {}

    public function createView(): void
    {
        echo $this->view->render("transactions/create.php");
    }

    public function create(): void
    {
        $this->formValidationService->validateTransaction($_POST);
        $this->transactionService->create($_POST);
        redirectTo("/");
    }
}