<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router;

use Spryker\Shared\Router\ChainRouter;
use Spryker\Shared\Router\Loader\ClosureLoader;
use Spryker\Shared\Router\Loader\LoaderInterface;
use Spryker\Shared\Router\Resource\ResourceInterface;
use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Shared\Router\Router;
use Spryker\Shared\Router\RouterInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Resource\RouterResource;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class RouterFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Router\ChainRouter
     */
    public function createRouter()
    {
        return new ChainRouter($this->getRouterPlugins());
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    protected function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Router\RouterInterface
     */
    public function createYvesRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getRouterConfiguration()
        );
    }

    /**
     * @return \Spryker\Shared\Router\Loader\LoaderInterface
     */
    protected function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @return \Spryker\Shared\Router\Resource\ResourceInterface
     */
    protected function createResource(): ResourceInterface
    {
        return new RouterResource(
            $this->createRouteCollection(),
            $this->getRouteProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function createRouteCollection(): RouteCollection
    {
        return new RouteCollection(
            $this->getRouteManipulatorPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface[]
     */
    protected function getRouteManipulatorPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ROUTE_MANIPULATOR);
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[]
     */
    protected function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ROUTE_PROVIDER);
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
    }
}
