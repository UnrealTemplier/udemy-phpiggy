<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    public function process(callable $next): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $validMethods = ["POST", "PATCH", "DELETE"];

        if (!in_array($requestMethod, $validMethods)) {
            $next();
            return;
        }

        if ($_SESSION["csrf_token"] !== $_POST["csrf_token"]) {
            redirectTo("/");
        }

        unset($_SESSION["csrf_token"]);

        $next();
    }
}