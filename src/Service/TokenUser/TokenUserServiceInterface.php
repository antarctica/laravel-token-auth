<?php

namespace Antarctica\LaravelTokenAuth\Service\TokenUser;

interface TokenUserServiceInterface {

    /**
     * @return mixed
     */
    public function getTokenInterface();

    /**
     * @return mixed
     */
    public function getUserInterface();

    /**
     * @param string $token
     * @return string
     */
    public function getUserIdentifier($token);

    /**
     * @param string $token
     * @return array
     */
    public function getUserEntity($token);

    /**
     * @param array $credentials
     * @return mixed
     */
    public function issue(array $credentials);

    /**
     * @param string $token
     */
    public function revoke($token);

    /**
     * @param $token
     * @return bool
     */
    public function validate($token);
}