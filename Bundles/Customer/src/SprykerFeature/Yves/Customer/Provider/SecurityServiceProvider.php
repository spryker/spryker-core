<?php

namespace SprykerFeature\Yves\Customer\Provider;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Customer\CustomerConfig;
use Silex\Application;
use Silex\Provider\SecurityServiceProvider as SilexSecurityServiceProvider;
use Generated\Zed\Ide\AutoCompletion;

class SecurityServiceProvider extends SilexSecurityServiceProvider
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface        $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);

        $app["security.firewalls"] = [
            "secured" => [
                "pattern" => Config::get(CustomerConfig::CUSTOMER_SECURED_PATTERN),
                "form" => [
                    "login_path" => "/login",
                    "login_check" => "/login_check",
                ],
                "logout" => [
                    "logout_path" => "/customer/logout",
                ],
                "users" => $app->share(function ($app) {
                    return $this->locator->customer()
                        ->pluginSecurityService()
                        ->createUserProvider($app["session"]);
                }),
            ],
            "unsecured" => [
                "pattern" => Config::get(CustomerConfig::CUSTOMER_ANONYMOUS_PATTERN),
                "anonymous" => true,
            ],
        ];
    }
}
