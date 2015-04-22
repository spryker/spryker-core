<?php

namespace SprykerFeature\Zed\Auth\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Auth\AuthConfig;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Library\Config;

class AuthSettings
{
    const AUTH_SESSION_KEY = "auth";
    const AUTH_CURRENT_USER_KEY = "%s:currentUser:%s";

    const AUTHORIZATION_WILDCARD = '*';

    /**
     * @var AutoCompletion
     */
    protected $locator;

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
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

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

        $users = $this->locator->user()->facade()->getSystemUsers();
        $credentials = Config::get(AuthConfig::AUTH_DEFAULT_CREDENTIALS);

        foreach ($users as $user) {
            if (isset($credentials[$user->getUsername()])) {
                $response[$user->getUsername()] = $credentials[$user->getUsername()];
            }
        }

        return $response;
    }
}
