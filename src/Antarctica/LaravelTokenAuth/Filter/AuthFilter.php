<?php

namespace Antarctica\LaravelTokenAuth\Filter;

use Carbon;
use Exception;
use Antarctica\LaravelTokenAuth\Service\TokenUser\TokenUserServiceInterface;
use Antarctica\LaravelTokenAuth\Exception\Token\MissingTokenException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AuthFilter {

    /**
     * @var TokenUserServiceInterface
     */
    private $TokenUser;

    private $session_authenticated = false;

    private $token_authenticated = false;

    private $user_authenticated = false;

    /**
     * @param TokenUserServiceInterface $TokenUser
     */
    function __construct(TokenUserServiceInterface $TokenUser)
    {
        $this->TokenUser = $TokenUser;
    }

    /**
     * Main method, if 'successful' this method should not return anything (even true).
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function filter($request)
    {
        // Check if user is authenticated via an auth session
        $this->session_authenticated = $this->isUserSessionAuthenticated();

        // Check if user is authenticated via an auth token
        $this->token_authenticated = $this->isUserTokenAuthenticated($request);

        // Decide if user is authenticated
        $this->user_authenticated = $this->isUserAuthenticated([$this->session_authenticated, $this->token_authenticated]);

        if ($this->user_authenticated === false)
        {
            // There is no way to know, for definite, which 'method' a user intended to authenticate, unless a token was
            // used, in which case it is safe to assume which method they were using.
            // In cases where a token wasn't given and the user has requested a JSON response, we assume they were trying
            // to use token authentication. Otherwise we assume they were trying to use session authentication.

            // Note: This approach maybe revised in the future.

            if (($this->token_authenticated !== false && $this->token_authenticated instanceof MissingTokenException === false) || Request::wantsJson())
            {
                $this->tokenAuthenticationFailure();
            }

            $this->sessionAuthenticationFailure();
            // BUG: For some reason issuing a redirect within a function other than this one doesn't do anything.
            return Redirect::guest('login');
        }
    }

    /**
     * Checks whether a user is authentication via the session, essentially a pass through to Laravel's Auth functions.
     * @return bool|string
     */
    private function isUserSessionAuthenticated()
    {
        if (Auth::guest())
        {
            return '401-not-authenticated';
        }

        return true;
    }

    /**
     * Validates an authentication token if provided, otherwise returns an exception that can be re-thrown later.
     * @return bool|Exception
     */
    private function isUserTokenAuthenticated()
    {
        try
        {
            $token = $this->TokenUser->getTokenInterface()->get();
            $this->TokenUser->validate($token);
        }
        catch (Exception $exception)
        {
            // We will re-throw this error later if no other authentication types give a successful result
            return $exception;
        }

        return true;
    }

    /**
     * Determines if any authentication method succeeded
     *
     * @param array $authentication_methods
     * @return bool
     */
    private function isUserAuthenticated(array $authentication_methods)
    {
        return in_array(true, $authentication_methods, $strict_checking = true);
    }

    /**
     * There is only one reason why session based authentication can fail
     */
    private function sessionAuthenticationFailure()
    {
        Session::flash('flash_message_info', 'Please login to continue.');
    }

    /**
     * There are multiple ways token authentication can fail, we re-throw the exception caught previously to provide to explain why.
     *
     * @throws Exception
     */
    private function tokenAuthenticationFailure()
    {
        /** @var \Exception $exception */
        $exception = $this->token_authenticated;

        throw $exception;
    }
}