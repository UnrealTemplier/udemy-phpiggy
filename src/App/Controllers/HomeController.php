<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TransactionService;
use Framework\TemplateEngine;

class HomeController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
    ) {}

    public function home(): void
    {
        $limit = 3;
        $page = (int)($_GET["p"] ?? 1);
        $offset = ($page - 1) * $limit;
        $searchTerm = $_GET["s"] ?? null;
        $previousPageQuery = http_build_query(["p" => $page - 1, "s" => $searchTerm]);

        $transactions = $this->transactionService->getUserTransactions($limit, $offset);

        echo $this->view->render(
            "index.php",
            [
                "transactions" => $transactions,
                "currentPage" => $page,
                "previousPageQuery" => $previousPageQuery,
            ],
        );
    }
}
