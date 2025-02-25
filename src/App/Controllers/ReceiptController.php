<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{ReceiptService, TransactionService};
use Framework\TemplateEngine;

class ReceiptController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private ReceiptService $receiptService,
    ) {}

    public function uploadView(array $params): void
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params["id"]);

        if (!$transaction) {
            redirectTo("/");
        }

        echo $this->view->render("receipts/create.php");
    }

    public function upload(array $params): void
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params["id"]);

        if (!$transaction) {
            redirectTo("/");
        }

        $receiptFile = $_FILES["receipt"] ?? null;
        $this->receiptService->validateFile($receiptFile);
        $this->receiptService->upload($receiptFile, (int)$params["id"]);

        redirectTo("/");
    }

    public function download(array $params): void
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params["transaction"]);
        if (!$transaction) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt((int)$params["receipt"]);
        if (!$receipt) {
            redirectTo("/");
        }

        if ($receipt["transaction_id"] !== $transaction["id"]) {
            redirectTo("/");
        }

        $this->receiptService->read($receipt);
    }

    public function delete(array $params): void
    {
        $transaction = $this->transactionService->getUserTransaction((int)$params["transaction"]);
        if (!$transaction) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt((int)$params["receipt"]);
        if (!$receipt) {
            redirectTo("/");
        }

        if ($receipt["transaction_id"] !== $transaction["id"]) {
            redirectTo("/");
        }

        $this->receiptService->delete($receipt);

        redirectTo("/");
    }
}