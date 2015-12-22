<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth;

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\User\UserConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AuthConfig extends AbstractBundleConfig
{

    const AUTH_SESSION_KEY = 'auth';
    const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    const AUTHORIZATION_WILDCARD = '*';
    const DAY_IN_SECONDS = 86400;
    const AUTH_TOKEN = 'Auth-Token';

    /**
     * @return string
     */
    public function getLoginPageUrl()
    {
        return '/auth/login';
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
        return self::DAY_IN_SECONDS;
    }

}
