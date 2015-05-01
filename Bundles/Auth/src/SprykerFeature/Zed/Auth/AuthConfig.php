<?php

namespace SprykerFeature\Zed\Auth;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Auth\AuthConfig as AuthSharedConfig;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Library\Config;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class AuthConfig extends AbstractBundleConfig
{
    const AUTH_SESSION_KEY = "auth";
    const AUTH_CURRENT_USER_KEY = "%s:currentUser:%s";

    const AUTHORIZATION_WILDCARD = '*';

    public function getLoginPageUrl()
    {
        return '/auth/login';
    }

    /**
     * @var array
     */
    protected $ignorable = [
        [
            'bundle' => "auth",
            'controller' => "login",
            'action' => "index"
        ],
        [
            'bundle' => "auth",
            'controller' => "login",
            'action' => "check"
        ],
        [
            'bundle' => "auth",
            'controller' => "login",
            'action' => "exit"
        ],
        [
            'bundle' => "auth",
            'controller' => "password",
            'action' => "reset"
        ]
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

    public function setIgnorable($bundle, $controller, $action)
    {
        $this->ignorable[] = [
            'bundle' => $bundle,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * @return array
     */
    public function getUsersCredentials()
    {
        $response = [];

        $users = $this->getLocator()->user()->facade()->getSystemUsers();
        $credentials = Config::get(AuthSharedConfig::AUTH_DEFAULT_CREDENTIALS);

        foreach ($users as $user) {
            if (isset($credentials[$user->getUsername()])) {
                $response[$user->getUsername()] = $credentials[$user->getUsername()];
            }
        }

        return $response;
    }
}
