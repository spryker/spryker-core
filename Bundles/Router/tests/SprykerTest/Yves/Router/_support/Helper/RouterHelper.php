<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Router\Loader\ClosureLoader;
use Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin;
use Spryker\Yves\Router\Route\Route;
use Spryker\Yves\Router\Route\RouteCollection;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use SprykerTest\Yves\Testify\Helper\ApplicationHelperTrait;
use Symfony\Component\Routing\Router;

class RouterHelper extends Module
{
    use ApplicationHelperTrait;
    use ContainerHelperTrait;

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @var \Spryker\Yves\Router\Route\RouteCollection|null
     */
    protected $routeCollection;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(new RouterApplicationPlugin());
    }

    /**
     * @param string $name
     * @param string $path
     * @param callable $controller
     * @param array $defaults
     * @param array $requirements
     * @param array $options
     * @param string|null $host
     * @param array $schemes
     * @param array $methods
     * @param string|null $condition
     *
     * @return void
     */
    public function addRoute(
        string $name,
        string $path,
        callable $controller,
        array $defaults = [],
        array $requirements = [],
        array $options = [],
        ?string $host = '',
        $schemes = [],
        $methods = [],
        ?string $condition = ''
    ): void {
        $defaults['_controller'] = $controller;
        $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);

        $this->getRouteCollection()->add($name, $route);

        $chainRouter = new ChainRouter([]);

        $loader = new ClosureLoader();
        $resource = function () {
            return $this->getRouteCollection();
        };
        $router = new Router($loader, $resource);
        $chainRouter->add($router);

        $this->getContainer()->set(static::SERVICE_ROUTER, $chainRouter);
    }

    /**
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function getRouteCollection(): RouteCollection
    {
        if ($this->routeCollection === null) {
            $this->routeCollection = new RouteCollection();
        }

        return $this->routeCollection;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        /** @var \Spryker\Service\Container\ContainerInterface $container */
        $container = $this->getContainerHelper()->getContainer();

        return $container;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->routeCollection = null;
    }
}
