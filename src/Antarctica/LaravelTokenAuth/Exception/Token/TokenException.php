<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

use Antarctica\LaravelBaseExceptions\Exception\HttpException;

class TokenException extends HttpException {

    protected $kind = 'unknown_authentication_token_fault';

    protected $details = [
        "token_error" => [
            "There is something wrong with the token authentication."
        ]
    ];

    protected $resolution = 'Try again or get a new token. If needed contact the API maintainer for assistance.';

    protected $resolutionURLs = ['mailto:basweb@bas.ac.uk'];
}