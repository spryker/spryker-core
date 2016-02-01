<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Auth\Business\Exception\UserNotLoggedException;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

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
     * @param \Generated\Shared\Transfer\UserTransfer $user
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
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getSystemUserByHash($hash);

    /**
     * @param string $token
     *
     * @throws \Spryker\Zed\Auth\Business\Exception\UserNotLoggedException
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
