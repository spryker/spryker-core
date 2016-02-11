<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Business;

interface AuthFacadeInterface
{

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login($username, $password);

    /**
     * @return void
     */
    public function logout();

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthenticated($token);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return string
     */
    public function getUserToken($user);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action);

    /**
     * @param string $hash
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getSystemUser($hash);

    /**
     * @return string
     */
    public function getCurrentUserToken();

    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset($email);

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken($token);

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($token, $password);

}
