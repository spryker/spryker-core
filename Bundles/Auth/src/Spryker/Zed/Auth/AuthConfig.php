<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth;

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\User\UserConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AuthConfig extends AbstractBundleConfig
{
    public const DEFAULT_URL_REDIRECT = '/';
    public const DEFAULT_URL_LOGIN = '/auth/login';

    /**
     * @return string
     */
    public function getLoginPageUrl()
    {
        return static::DEFAULT_URL_LOGIN;
    }

    /**
     * @var array
     */
    protected $ignorable = [
        [
            'bundle' => 'auth',
            'controller' => 'login',
            'action' => 'index',
        ],
        [
            'bundle' => 'auth',
            'controller' => 'login',
            'action' => 'check',
        ],
        [
            'bundle' => 'auth',
            'controller' => 'login',
            'action' => 'exit',
        ],
        [
            'bundle' => 'auth',
            'controller' => 'password',
            'action' => 'reset',
        ],
        [
            'bundle' => 'auth',
            'controller' => 'password',
            'action' => 'reset-request',
        ],
    ];

    /**
     * @var array
     */
    protected $envConfigurations = [];

    /**
     * @return array
     */
    public function getIgnorable()
    {
        return $this->ignorable;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return void
     */
    public function addIgnorable($bundle, $controller, $action)
    {
        $this->ignorable[] = [
            'bundle' => $bundle,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    /**
     * @return array
     */
    public function getUsersCredentials()
    {
        $response = [];

        $credentials = $this->get(AuthConstants::AUTH_DEFAULT_CREDENTIALS);

        foreach ($this->getSystemUsers() as $username) {
            if (isset($credentials[$username])) {
                $response[$username] = $credentials[$username];
            }
        }

        return $response;
    }

    /**
     * @return array
     */
    protected function getSystemUsers()
    {
        return $this->get(UserConstants::USER_SYSTEM_USERS);
    }

    /**
     * @return int
     */
    public function getPasswordTokenExpirationInSeconds()
    {
        return AuthConstants::DAY_IN_SECONDS;
    }
}
