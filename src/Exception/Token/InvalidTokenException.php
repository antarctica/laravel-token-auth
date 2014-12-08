<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

class InvalidTokenException extends TokenException {

    protected $statusCode = 400;

    protected $kind = 'invalid_authentication_token';

    protected $details = [
        "token_error" => [
            "The authentication token given is not valid or is malformed."
        ]
    ];

    protected $resolution = 'Check the token you have provided is complete, or get a new token.';
}