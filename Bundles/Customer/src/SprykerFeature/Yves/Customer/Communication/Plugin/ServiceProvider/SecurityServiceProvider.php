<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Shared\Config;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Customer\CustomerConfig;
use SprykerFeature\Yves\Customer\Communication\Handler\AjaxAuthenticationHandler;
use SprykerFeature\Yves\Customer\Communication\Plugin\UserProvider;

class SecurityServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @param UserProvider $userProvider
     *
     * @return SecurityServiceProvider
     */
    public function setUserProvider($userProvider)
    {
        $this->userProvider = $userProvider;

        return $this;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['security.authentication.success_handler._proto'] = $app->protect(function ($name, $options) use ($app) {
            return $app->share(function () use ($name, $options, $app) {
                return new AjaxAuthenticationHandler();
            });
        });

        $app['security.authentication.failure_handler._proto'] = $app->protect(function ($name, $options) use ($app) {
            return $app->share(function () use ($name, $options, $app) {
                return new AjaxAuthenticationHandler();
            });
        });

        $app['security.firewalls'] = [
            'secured' => [
                'anonymous' => true,
                'pattern' => '^/',
                'form' => [
                    'login_path' => '/login',
                    'login_check' => '/login_check',
                ],
                'logout' => [
                    'logout_path' => '/customer/logout',
                ],
                'users' => $app->share(function ($app) {
                    return $this->userProvider;
                }),
            ],
        ];

        $app['security.access_control'] = [
            [
                'path' => '^/login',
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ],
            [
                'path' => Config::get(CustomerConfig::CUSTOMER_SECURED_PATTERN),
                'role' => 'ROLE_USER',
            ],
            [
                'path' => Config::get(CustomerConfig::CUSTOMER_ANONYMOUS_PATTERN),
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ],
        ];
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }

}
