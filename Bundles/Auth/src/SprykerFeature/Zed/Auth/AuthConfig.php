<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth;

use SprykerFeature\Shared\Auth\AuthConfig as AuthSharedConfig;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class AuthConfig extends AbstractBundleConfig
{

    const AUTH_SESSION_KEY = 'auth';
    const AUTH_CURRENT_USER_KEY = '%s:currentUser:%s';
    const AUTHORIZATION_WILDCARD = '*';
    const DAY_IN_SECONDS = 86400;

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

        $users = $this->getLocator()->user()->facade()->getSystemUsers();
        $credentials = $this->get(AuthSharedConfig::AUTH_DEFAULT_CREDENTIALS);

        foreach ($users->getUser() as $transferUser) {
            $username = $transferUser->getUsername();
            if (isset($credentials[$username])) {
                $response[$username] = $credentials[$username];
            }
        }

        return $response;
    }

    /**
     * @return int
     */
    public function getPasswordTokenExpirationInSeconds()
    {
        return self::DAY_IN_SECONDS;
    }

}
