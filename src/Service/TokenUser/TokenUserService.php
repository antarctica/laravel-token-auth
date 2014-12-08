<?php

namespace Antarctica\LaravelTokenAuth\Service\TokenUser;

use Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lions\Exception\Token\UnknownSubjectTokenException;
use Lions\Service\Token\TokenServiceInterface;

use Lions\Repository\TokenBlacklist\TokenBlacklistRepositoryInterface;  // TODO: Circular dependency (!)
use Lions\Repository\User\UserRepositoryInterface;  // // TODO: How to specify (include interface in package?)

class TokenUserService implements TokenUserServiceInterface {

    /**
     * @var TokenServiceInterface
     */
    protected $Token;
    /**
     * @var UserRepositoryInterface
     */
    protected $User;
    /**
     * @var TokenBlacklistRepositoryInterface
     */
    private $Blacklist;

    /**
     * @param TokenServiceInterface $Token
     * @param UserRepositoryInterface $User
     * @param TokenBlacklistRepositoryInterface $Blacklist
     */
    function __construct(TokenServiceInterface $Token, UserRepositoryInterface $User, TokenBlacklistRepositoryInterface $Blacklist)
    {
        $this->Token = $Token;
        $this->User = $User;
        $this->Blacklist = $Blacklist;
    }

    /**
     * @return TokenServiceInterface
     */
    public function getTokenInterface()
    {
        return $this->Token;
    }

    /**
     * @return UserRepositoryInterface
     */
    public function getUserInterface()
    {
        return $this->User;
    }

    /**
     * @param string $token
     * @return string
     */
    public function getUserIdentifier($token)
    {
        return $this->Token->getSubject($token);
    }

    /**
     * @param string $token
     * @return array
     * @throws UnknownSubjectTokenException
     */
    public function getUserEntity($token)
    {
        $tokenSubject = $this->getUserIdentifier($token);

        try
        {
            $tokenUser = $this->User->find($tokenSubject);
        }
        catch(ModelNotFoundException $exception)
        {
            throw new UnknownSubjectTokenException();
        }

        return $tokenUser;
    }

    /**
     * @param array $credentials
     * @return string
     */
    public function issue(array $credentials)
    {
        return $this->Token->authOnce($credentials);
    }

    /**
     * @param string $token
     * @return mixed
     */
    public function revoke($token)
    {
        return $this->Blacklist->create(['token' => $token]);
    }

    /**
     * @param $token
     * @return bool
     */
    public function validate($token)
    {
        // Check token user exists (and by extension that the token is valid)
        $this->getUserEntity($token);

        // Check token isn't blacklisted
        $this->Blacklist->check($token);

        return true;
    }
}