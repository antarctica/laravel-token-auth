<?php

namespace Antarctica\LaravelTokenAuth\Exception\Auth;

use Antarctica\LaravelBaseExceptions\Exception\HttpException;

class AuthenticationException extends HttpException {

    protected $statusCode = 401;

    protected $kind = 'authentication_failure';

    protected $details = [
        "authentication_error" => [
            "Ensure your credentials are correct and that your user account is still active, or contact the maintainer of this API for assistance."
        ]
    ];

    protected $resolution = '';
}