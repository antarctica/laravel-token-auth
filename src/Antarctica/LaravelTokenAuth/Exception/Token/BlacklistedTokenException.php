<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

class BlacklistedTokenException extends TokenException {

    protected $statusCode = 401;

    protected $kind = 'blacklisted_authentication_token';

    protected $details = [
        "token_error" => [
            "Authentication token has been blacklisted and can no longer be used."
        ]
    ];

    protected $resolution = 'Get a new token by re-authenticating.';
}