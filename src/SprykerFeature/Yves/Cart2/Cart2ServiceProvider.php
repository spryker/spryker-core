<?php

namespace SprykerFeature\Yves\Cart2;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Yves\Kernel\Factory;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart2ServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Cart2DependencyContainer
     */
    private $dependencyContainer;

    /**
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->dependencyContainer = $factory->create('DependencyContainer', $factory, $locator);
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['cart'] = $app->share(function ($app) {
            /** @var SessionInterface $session */
            $session = $app['session'];

            return $this->getDependencyContainer()->createCartSdk($session);
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return DependencyContainerInterface|Cart2DependencyContainer
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }
}
