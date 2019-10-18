<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Spryker\Shared\Router\Cache\CacheInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Router\Business\Cache\Cache;
use Spryker\Zed\Router\Business\Loader\ClosureLoader;
use Spryker\Zed\Router\Business\Loader\LoaderInterface;
use Spryker\Zed\Router\Business\Resource\ResourceInterface;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\Resource\RouterResource;
use Spryker\Zed\Router\Business\Router\Router;
use Spryker\Zed\Router\Business\Router\RouterInterface;
use Spryker\Zed\Router\RouterDependencyProvider;

/**
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 */
class RouterBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createRouter(): ChainRouter
    {
        return new ChainRouter($this->getRouterPlugins());
    }

    /**
     * @return \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    public function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createZedRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getRouterConfiguration()
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createZedDevelopmentRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getDevelopmentRouterConfiguration()
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Loader\LoaderInterface
     */
    public function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @return \Spryker\Zed\Router\Business\Resource\ResourceInterface
     */
    public function createResource(): ResourceInterface
    {
        return new RouterResource(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    public function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Router\Cache\CacheInterface
     */
    public function createCache(): CacheInterface
    {
        return new Cache($this->createRouter(), $this->getConfig());
    }
}
