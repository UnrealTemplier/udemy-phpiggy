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
        $currentPage = (int)($_GET["p"] ?? 1);
        $offset = ($currentPage - 1) * $limit;

        $searchTerm = $_GET["s"] ?? null;

        $previousPageQuery = http_build_query(["p" => $currentPage - 1, "s" => $searchTerm]);
        $nextPageQuery = http_build_query(["p" => $currentPage + 1, "s" => $searchTerm]);

        [$transactions, $count] = $this->transactionService->getUserTransactions($limit, $offset);

        $lastPage = ceil($count / $limit);

        echo $this->view->render(
            "index.php",
            [
                "transactions" => $transactions,
                "currentPage" => $currentPage,
                "lastPage" => $lastPage,
                "previousPageQuery" => $previousPageQuery,
                "nextPageQuery" => $nextPageQuery,
            ],
        );
    }
}
