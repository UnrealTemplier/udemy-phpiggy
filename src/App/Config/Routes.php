<?php

declare(strict_types=1);

namespace App\Config;

use App\Controllers\{AboutController,
    AuthController,
    ErrorController,
    HomeController,
    ReceiptController,
    TransactionController};
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};
use Framework\App;

function registerRoutes(App $app): void
{
    $app
        ->get("/", [HomeController::class, "home"])
        ->add(AuthRequiredMiddleware::class);

    $app->get("/about", [AboutController::class, "about"]);

    $app
        ->get("/register", [AuthController::class, "registerView"])
        ->add(GuestOnlyMiddleware::class);

    $app
        ->post("/register", [AuthController::class, "register"])
        ->add(GuestOnlyMiddleware::class);

    $app
        ->get("/login", [AuthController::class, "loginView"])
        ->add(GuestOnlyMiddleware::class);

    $app
        ->post("/login", [AuthController::class, "login"])
        ->add(GuestOnlyMiddleware::class);

    $app
        ->get("/logout", [AuthController::class, "logout"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->get("/transaction", [TransactionController::class, "createView"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->post("/transaction", [TransactionController::class, "create"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->get("/transaction/{id}", [TransactionController::class, "editView"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->post("/transaction/{id}", [TransactionController::class, "edit"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->delete("/transaction/{id}", [TransactionController::class, "delete"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->get("/transaction/{id}/receipt", [ReceiptController::class, "uploadView"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->post("/transaction/{id}/receipt", [ReceiptController::class, "upload"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->get("/transaction/{transaction}/receipt/{receipt}", [ReceiptController::class, "download"])
        ->add(AuthRequiredMiddleware::class);

    $app
        ->delete("/transaction/{transaction}/receipt/{receipt}", [ReceiptController::class, "delete"])
        ->add(AuthRequiredMiddleware::class);

    $app->setErrorHandler([ErrorController::class, "notFound"]);
}
