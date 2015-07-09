<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\Auth\Business\Exception\UserNotLoggedException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;

interface AuthInterface
{

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function authenticate($username, $password);

    /**
     * @param UserTransfer $user
     *
     * @return string
     */
    public function generateToken(UserTransfer $user);

    /**
     * @return bool
     */
    public function logout();

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthorized($token);

    /**
     * @return string
     */
    public function getCurrentUserToken();

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param string $hash
     *
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getSystemUserByHash($hash);

    /**
     * @param string $token
     *
     * @throws UserNotLoggedException
     *
     * @return mixed
     */
    public function getCurrentUser($token);

    /**
     * @param string $token
     *
     * @return bool
     */
    public function userTokenIsValid($token);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorablePath($bundle, $controller, $action);

}
