<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business\Model;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\AuthBusinessFactory;
use Spryker\Zed\Auth\Business\Client\StaticToken;
use Spryker\Zed\Auth\Business\Exception\UserNotLoggedException;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Generated\Shared\Transfer\UserTransfer;

class Auth implements AuthInterface
{

    /**
     * @var SessionClientInterface
     */
    protected $session;

    /**
     * @var AuthToUserBridge
     */
    protected $userFacade;

    /**
     * @var AuthBusinessFactory
     */
    protected $businessFactory;

    /**
     * @var AuthConfig
     */
    protected $authConfig;

    /**
     * @var StaticToken
     */
    protected $staticToken;

    /**
     * @param SessionClientInterface $session
     * @param AuthToUserBridge $userBridge
     * @param AuthConfig $authConfig
     * @param StaticToken $staticToken
     */
    public function __construct(
        SessionClientInterface $session,
        AuthToUserBridge $userBridge,
        AuthConfig $authConfig,
        StaticToken $staticToken
    ) {
        $this->session = $session;
        $this->userFacade = $userBridge;
        $this->authConfig = $authConfig;
        $this->staticToken = $staticToken;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $hasUser = $this->userFacade->hasUserByUsername($username);
        if (!$hasUser) {
            return false;
        }

        $userTransfer = $this->userFacade->getUserByUsername($username);

        $isValidPassword = $this->userFacade->isValidPassword($password, $userTransfer->getPassword());
        if (!$isValidPassword) {
            return false;
        }

        $userTransfer->setLastLogin((new \DateTime())->format(\DateTime::ATOM));

        $token = $this->generateToken($userTransfer);

        $this->registerAuthorizedUser($token, $userTransfer);

        $userTransfer->setPassword(null);
        $this->userFacade->updateUser($userTransfer);

        return true;
    }

    /**
     * @param UserTransfer $user
     *
     * @return string
     */
    public function generateToken(UserTransfer $user)
    {
        return md5(sprintf('%s%s', $user->getPassword(), $user->getIdUser()));
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function getSessionKey($token)
    {
        return sprintf(AuthConstants::AUTH_CURRENT_USER_KEY, AuthConstants::AUTH_SESSION_KEY, $token);
    }

    /**
     * @param string $token
     * @param UserTransfer $userTransfer
     *
     * @return string
     */
    protected function registerAuthorizedUser($token, UserTransfer $userTransfer)
    {
        $key = $this->getSessionKey($token);
        $this->session->set($key, serialize($userTransfer));

        $this->userFacade->setCurrentUser($userTransfer);

        return unserialize($this->session->get($key));
    }

    /**
     * @return void
     */
    public function logout()
    {
        $token = $this->getCurrentUserToken();
        $key = $this->getSessionKey($token);

        $this->session->remove($key);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthorized($token)
    {
        if ($this->authorizeStaticToken($token)) {
            return true;
        }

        return $this->authorizeUserToken($token);
    }

    /**
     * This is based on sessions so the token will only be valid during a session lifetime
     *
     * @param string $token
     *
     * @return bool
     */
    protected function authorizeUserToken($token)
    {
        if ($this->userTokenIsValid($token) === false) {
            return false;
        }

        $currentUser = $this->getCurrentUser($token);

        try {
            $realUser = $this->userFacade->getUserById($currentUser->getIdUser());
        } catch (UserNotFoundException $e) {
            return false;
        }

        return $realUser->getPassword() === $currentUser->getPassword();
    }

    /**
     * @return string
     */
    public function getCurrentUserToken()
    {
        $user = $this->userFacade->getCurrentUser();

        return $this->generateToken($user);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->userFacade->hasCurrentUser();
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    protected function authorizeStaticToken($hash)
    {
        try {
            $user = $this->getSystemUserByHash($hash);
            $this->registerAuthorizedUser($hash, $user);

            return true;
        } catch (UserNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    public function hasSystemUserByHash($hash)
    {
        $credentials = $this->authConfig->getUsersCredentials();
        $token = $this->staticToken;
        foreach ($credentials as $username => $credential) {
            $token->setRawToken($credential['token']);
            if ($token->check($hash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $hash
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getSystemUserByHash($hash)
    {
        $user = new UserTransfer();

        $credentials = $this->authConfig->getUsersCredentials();
        $token = $this->staticToken;
        foreach ($credentials as $username => $credential) {
            $token->setRawToken($credential['token']);
            if ($token->check($hash) === true) {
                $user->setFirstName($username);
                $user->setLastName($username);
                $user->setUsername($username);
                $user->setPassword($username);

                return $user;
            }
        }

        throw new UserNotFoundException();
    }

    /**
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser($token)
    {
        $user = $this->unserializeUserFromSession($token);

        return $user;
    }

    /**
     * @param string $token
     *
     * @throws \Spryker\Zed\Auth\Business\Exception\UserNotLoggedException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function unserializeUserFromSession($token)
    {
        $key = $this->getSessionKey($token);
        $user = unserialize($this->session->get($key));

        if ($user === false) {
            throw new UserNotLoggedException();
        }

        return $user;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function userTokenIsValid($token)
    {
        $key = $this->getSessionKey($token);
        $user = unserialize($this->session->get($key));

        return $user !== false;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorablePath($bundle, $controller, $action)
    {
        $ignorable = $this->authConfig->getIgnorable();
        foreach ($ignorable as $ignore) {
            if (($bundle === $ignore['bundle'] || $ignore['bundle'] === AuthConstants::AUTHORIZATION_WILDCARD) &&
                ($controller === $ignore['controller'] || $ignore['controller'] === AuthConstants::AUTHORIZATION_WILDCARD) &&
                ($action === $ignore['action'] || $ignore['action'] === AuthConstants::AUTHORIZATION_WILDCARD)
            ) {
                return true;
            }
        }

        return false;
    }

}
