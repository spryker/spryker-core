<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Spryker\Shared\Router\ChainRouter;
use Spryker\Shared\Router\Loader\ClosureLoader;
use Spryker\Shared\Router\Loader\LoaderInterface;
use Spryker\Shared\Router\Resource\ResourceInterface;
use Spryker\Shared\Router\Router;
use Spryker\Shared\Router\RouterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Router\Business\Router\Resource\RouterResource;
use Spryker\Zed\Router\RouterDependencyProvider;

/**
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 */
class RouterBusinessFactory extends AbstractBusinessFactory
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
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
    }
}
