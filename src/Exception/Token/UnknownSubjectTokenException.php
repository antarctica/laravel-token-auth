<?php

namespace Antarctica\LaravelTokenAuth\Exception\Token;

class UnknownSubjectTokenException extends TokenException {

    protected $statusCode = 404;

    protected $kind = 'authentication_token_unknown_subject';

    protected $details = [
        "token_error" => [
            "The subject for the authentication token is unknown."
        ]
    ];

    protected $resolution = 'Ensure your user account is still active or contact the maintainer of this API for assistance.';

    protected $resolutionURLs = ['mailto:basweb@bas.ac.uk'];
}