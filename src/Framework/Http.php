<?php

declare(strict_types=1);

namespace Framework;

class Http
{
    public const STATUS_OK = 200;
    public const STATUS_REDIRECT = 302;
    public const STATUS_BAD_REQUEST = 400;
    public const STATUS_UNAUTHORIZED = 401;
    public const STATUS_FORBIDDEN = 403;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_INTERNAL_SERVER_ERROR = 500;
    public const STATUS_BAD_GATEWAY = 502;
    public const STATUS_SERVICE_UNAVAILABLE = 503;
}