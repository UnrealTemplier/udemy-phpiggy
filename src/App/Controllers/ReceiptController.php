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

    private function validateUserTransactionAndReceipt(
        int $transactionId,
        int $receiptId,
    ): mixed {
        $this->transactionService->validateUserTransaction($transactionId);

        $receipt = $this->receiptService->getReceipt($receiptId);
        if (!$receipt) {
            redirectTo("/");
        }

        if ($receipt["transaction_id"] !== $transactionId) {
            redirectTo("/");
        }

        return $receipt;
    }

    public function uploadView(array $params): void
    {
        $id = (int)$params["id"];
        $this->transactionService->validateUserTransaction($id);

        echo $this->view->render("receipts/create.php");
    }

    public function upload(array $params): void
    {
        $id = (int)$params["id"];
        $this->transactionService->validateUserTransaction($id);

        $receiptFile = $_FILES["receipt"] ?? null;
        $this->receiptService->validateFile($receiptFile);
        $this->receiptService->upload($receiptFile, $id);

        redirectTo("/");
    }

    public function download(array $params): void
    {
        $receipt = $this->validateUserTransactionAndReceipt((int)$params["transaction"], (int)$params["receipt"]);
        $this->receiptService->read($receipt);
    }

    public function delete(array $params): void
    {
        $receipt = $this->validateUserTransactionAndReceipt((int)$params["transaction"], (int)$params["receipt"]);
        $this->receiptService->delete($receipt);

        redirectTo("/");
    }
}