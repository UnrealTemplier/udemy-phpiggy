<?php

declare(strict_types=1);

use App\Config\Paths;
use App\Services\{FormValidationService, ReceiptService, TransactionService, UserService};
use Framework\{Container, Database, TemplateEngine};

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEWS),
    FormValidationService::class => fn() => new FormValidationService(),
    Database::class => fn() => new Database($_ENV["DB_DRIVER"], [
        "host" => $_ENV["DB_HOST"],
        "port" => $_ENV["DB_PORT"],
        "dbname" => $_ENV["DB_NAME"],
    ], $_ENV["DB_USER"], $_ENV["DB_PASS"]),
    UserService::class => function (Container $container) {
        $db = $container->getDependency(Database::class);
        return new UserService($db);
    },
    TransactionService::class => function (Container $container) {
        $db = $container->getDependency(Database::class);
        return new TransactionService($db);
    },
    ReceiptService::class => function (Container $container) {
        $db = $container->getDependency(Database::class);
        return new ReceiptService($db);
    },
];
