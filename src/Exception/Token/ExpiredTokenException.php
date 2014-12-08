<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

class ExpiredTokenException extends TokenException {

    protected $statusCode = 401;

    protected $kind = 'expired_authentication_token';

    protected $details = [
        "token_error" => [
            "The authentication token given has expired and is no longer valid."
        ]
    ];

    protected $resolution = 'Get a new token by re-authenticating.';
}