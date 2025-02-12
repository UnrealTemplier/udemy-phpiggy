<?php

declare(strict_types=1);

namespace App\Config;

use App\Middleware\{CsrfGuardMiddleware,
    CsrfTokenMiddleware,
    FlashMiddleware,
    SessionMiddleware,
    TemplateDataMiddleware,
    ValidationExceptionMiddleware};
use Framework\App;

function registerMiddleware(App $app): void
{
    $app->addMiddleware(CsrfGuardMiddleware::class);
    $app->addMiddleware(CsrfTokenMiddleware::class);
    $app->addMiddleware(TemplateDataMiddleware::class);
    $app->addMiddleware(ValidationExceptionMiddleware::class);
    $app->addMiddleware(FlashMiddleware::class);
    $app->addMiddleware(SessionMiddleware::class);
}
