<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

class MissingTokenException extends TokenException {

    protected $statusCode = 401;

    protected $kind = 'missing_authentication_token';

    protected $details = [
        "token_error" => [
            "No authentication token was given and no authentication session exists."
        ]
    ];

    protected $resolution = 'Include a token in the \'authorization\' header, using the bearer scheme (i.e. \'Authoization: Bearer [token]\'.';
}