<?php

namespace Antarctica\LaravelTokenAuth\Service\Token;

use JWTAuth;
use Antarctica\LaravelTokenAuth\Exception\Auth\AuthenticationException;
use Antarctica\LaravelTokenAuth\Exception\Token\ExpiredTokenException;
use Antarctica\LaravelTokenAuth\Exception\Token\InvalidTokenException;
use Antarctica\LaravelTokenAuth\Exception\Token\MissingTokenException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class TokenServiceJwtAuth implements TokenServiceInterface {

    /**
     * @return string
     * @throws MissingTokenException
     */
    public function get()
    {
        $token = JWTAuth::getToken();

        // Ensure a token was actually found
        if ($token === false || $token === null)
        {
            throw new MissingTokenException();
        }

        return $token;
    }

    /**
     * @param string $token
     * @return string
     * @throws ExpiredTokenException
     * @throws InvalidTokenException
     */
    public function decode($token)
    {
        try {
            $token = JWTAuth::decode($token);
        }
        catch (TokenExpiredException $exception)
        {
            throw new ExpiredTokenException();
        }
        catch (JWTException $exception)
        {
            throw new InvalidTokenException();
        }

        return $token;
    }

    /**
     * @param string $token
     * @return string
     */
    public function getSubject($token)
    {
        $tokenDecoded = $this->decode($token);

        return $tokenDecoded['sub'];
    }

    /**
     * @param string $token
     * @return string
     */
    public function getExpiry($token)
    {
        $tokenDecoded = $this->decode($token);

        return $tokenDecoded['exp'];
    }

    /**
     * @param $credentials
     * @return string
     * @throws AuthenticationException
     */
    public function authOnce($credentials)
    {
        $token = JWTAuth::attempt($credentials);

        if ($token === false)
        {
            throw new AuthenticationException();
        }

        return $token;
    }
}