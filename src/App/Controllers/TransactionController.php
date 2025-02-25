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

    public function editView(array $params): void
    {
        $transaction = $this->transactionService->validateUserTransaction((int)$params["id"]);

        echo $this->view->render(
            "transactions/edit.php",
            [
                "transaction" => $transaction,
            ],
        );
    }

    public function edit(array $params): void
    {
        $id = (int)$params["id"];
        $this->transactionService->validateUserTransaction($id);

        $this->formValidationService->validateTransaction($_POST);
        $this->transactionService->update($id, $_POST);
        redirectTo($_SERVER["HTTP_REFERER"]);
    }

    public function delete(array $params): void
    {
        $id = (int)$params["id"];
        $this->transactionService->validateUserTransaction($id);

        $this->transactionService->delete($id);
        redirectTo("/");
    }
}