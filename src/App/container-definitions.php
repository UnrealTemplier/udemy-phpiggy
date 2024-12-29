<?php

declare(strict_types=1);

use App\Config\Paths;
use App\Services\UserService;
use App\Services\FormValidationService;
use Framework\{Container, TemplateEngine, Database};

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEWS),
    FormValidationService::class => fn() => new FormValidationService(),
    Database::class => fn() => new Database($_ENV["DB_DRIVER"], [
        "host"      => $_ENV["DB_HOST"],
        "port"      => $_ENV["DB_PORT"],
        "dbname"    => $_ENV["DB_NAME"],
    ], $_ENV["DB_USER"], $_ENV["DB_PASS"]),
    UserService::class => function (Container $container) {
        $db = $container->getDependency(Database::class);
        return new UserService($db);
    },
];
