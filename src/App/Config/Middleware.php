<?php

declare(strict_types=1);

namespace App\Config;

use App\Middleware\{FlashMiddleware, SessionMiddleware, TemplateDataMiddleware, ValidationExceptionMiddleware};
use Framework\App;

function registerMiddleware(App $app): void
{
    $app->addMiddleware(TemplateDataMiddleware::class);
    $app->addMiddleware(ValidationExceptionMiddleware::class);
    $app->addMiddleware(FlashMiddleware::class);
    $app->addMiddleware(SessionMiddleware::class);
}
