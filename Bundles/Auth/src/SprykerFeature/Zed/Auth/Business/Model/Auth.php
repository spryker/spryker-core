<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Client\Session\Service\SessionClientInterface;
use SprykerFeature\Zed\Auth\AuthConfig;
use SprykerFeature\Zed\Auth\Business\AuthDependencyContainer;
use SprykerFeature\Zed\Auth\Business\Client\StaticToken;
use SprykerFeature\Zed\Auth\Business\Exception\UserNotLoggedException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use Generated\Shared\Transfer\UserTransfer;

class Auth implements AuthInterface
{

    /**
     * @var AutoCompletion
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @var SessionClientInterface
     */
    protected $session;

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * @var AuthDependencyContainer
     */
    protected $dependencyContainer;

    /**
     * @todo cleanup dependencies
     *
     * @param LocatorLocatorInterface $locator
     * @param SessionClientInterface $session
     * @param UserFacade $userFacade
     * @param AuthConfig $settings
     * @param StaticToken $staticToken
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        SessionClientInterface $session,
        UserFacade $userFacade,
        AuthConfig $settings,
        StaticToken $staticToken
    ) {
        $this->locator = $locator;
        $this->session = $session;
        $this->userFacade = $userFacade;
        $this->bundleSettings = $settings;
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
        if (false === $hasUser) {
            return false;
        }

        $user = $this->userFacade->getUserByUsername($username);

        $isValidPassword = $this->userFacade->isValidPassword($password, $user->getPassword());
        if (false === $isValidPassword) {
            return false;
        }

        $token = $this->generateToken($user);

        $users = $this->registerAuthorizedUser($token, $user);

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
        return sprintf(AuthConfig::AUTH_CURRENT_USER_KEY, AuthConfig::AUTH_SESSION_KEY, $token);
    }

    /**
     * @param string $token
     * @param UserTransfer $user
     *
     * @return string
     */
    protected function registerAuthorizedUser($token, UserTransfer $user)
    {
        $key = $this->getSessionKey($token);
        $this->session->set($key, serialize($user));

        $this->userFacade->setCurrentUser($user);

        return unserialize($this->session->get($key));
    }

    /**
     *
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
        if (false === $this->userTokenIsValid($token)) {
            return false;
        }

        $currentUser = $this->getCurrentUser($token);

        $realUser = $this->userFacade->getUserById($currentUser->getIdUser());

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
        $credentials = $this->bundleSettings->getUsersCredentials();
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
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getSystemUserByHash($hash)
    {
        $user = new UserTransfer();

        $credentials = $this->bundleSettings->getUsersCredentials();
        $token = $this->staticToken;
        foreach ($credentials as $username => $credential) {
            $token->setRawToken($credential['token']);
            if (true === $token->check($hash)) {
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
     * @return UserTransfer
     */
    public function getCurrentUser($token)
    {
        $user = $this->unserializeUserFromSession($token);

        return $user;
    }

    /**
     * @param string $token
     *
     * @throws UserNotLoggedException
     *
     * @return UserTransfer
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
        $ignorable = $this->bundleSettings->getIgnorable();
        foreach ($ignorable as $ignore) {
            if (($bundle === $ignore['bundle'] || $ignore['bundle'] === AuthConfig::AUTHORIZATION_WILDCARD) &&
                ($controller === $ignore['controller'] || $ignore['controller'] === AuthConfig::AUTHORIZATION_WILDCARD) &&
                ($action === $ignore['action'] || $ignore['action'] === AuthConfig::AUTHORIZATION_WILDCARD)
            ) {
                return true;
            }
        }

        return false;
    }

}
