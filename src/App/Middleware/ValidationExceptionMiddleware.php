<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            $oldFormData = $_POST;
            $excludedFields = ["password", "confirmPassword"];
            $formattedOldFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields),
            );
            $_SESSION["oldFormData"] = $formattedOldFormData;

            $_SESSION["errors"] = $e->errors;

            $referrer = $_SERVER["HTTP_REFERER"];
            redirectTo($referrer);
        }
    }
}
