<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Spryker\Shared\Router\Cache\CacheInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Router\Business\Cache\BackendGatewayCacheWarmer;
use Spryker\Zed\Router\Business\Cache\BackofficeCacheWarmer;
use Spryker\Zed\Router\Business\Cache\Cache;
use Spryker\Zed\Router\Business\Cache\MerchantPortalCacheWarmer;
use Spryker\Zed\Router\Business\Loader\ClosureLoader;
use Spryker\Zed\Router\Business\Loader\LoaderInterface;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\Router;
use Spryker\Zed\Router\Business\Router\RouterInterface;
use Spryker\Zed\Router\Business\Router\RouterResource\BackendGatewayRouterResource;
use Spryker\Zed\Router\Business\Router\RouterResource\BackofficeRouterResource;
use Spryker\Zed\Router\Business\Router\RouterResource\MerchantPortalRouterResource;
use Spryker\Zed\Router\Business\Router\RouterResource\RouterResource;
use Spryker\Zed\Router\Business\RouterResource\ResourceInterface;
use Spryker\Zed\Router\RouterDependencyProvider;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 */
class RouterBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createBackofficeChainRouter(): ChainRouter
    {
        return new ChainRouter($this->getBackofficeRouterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getBackofficeRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_BACKOFFICE_ROUTER);
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createMerchantPortalChainRouter(): ChainRouter
    {
        return new ChainRouter($this->getMerchantPortalRouterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getMerchantPortalRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_MERCHANT_PORTAL_ROUTER);
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createBackofficeRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createBackofficeRouterResource(),
            $this->getBackofficeRouterEnhancerPlugins(),
            $this->getConfig()->getBackofficeRouterConfiguration(),
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createMerchantPortalRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createMerchantPortalRouterResource(),
            $this->getMerchantPortalRouterEnhancerPlugins(),
            $this->getConfig()->getMerchantPortalRouterConfiguration(),
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    public function createBackofficeRouterResource(): ResourceInterface
    {
        return new BackofficeRouterResource(
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface>
     */
    public function getBackofficeRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_BACKOFFICE_ROUTER_ENHANCER);
    }

    /**
     * @return \Spryker\Shared\Router\Cache\CacheInterface
     */
    public function createBackofficeCacheWarmer(): CacheInterface
    {
        return new BackofficeCacheWarmer(
            $this->createBackofficeChainRouter(),
            $this->createFilesystem(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createBackendGatewayChainRouter(): ChainRouter
    {
        return new ChainRouter($this->getBackendGatewayRouterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getBackendGatewayRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_BACKEND_GATEWAY_ROUTER);
    }

    /**
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    public function createMerchantPortalRouterResource(): ResourceInterface
    {
        return new MerchantPortalRouterResource(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createBackendGatewayRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createBackendGatewayRouterResource(),
            $this->getBackendGatewayRouterEnhancerPlugins(),
            $this->getConfig()->getBackendGatewayRouterConfiguration(),
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    public function createBackendGatewayRouterResource(): ResourceInterface
    {
        return new BackendGatewayRouterResource(
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface>
     */
    public function getMerchantPortalRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_MERCHANT_PORTAL_ROUTER_ENHANCER);
    }

    /**
     * @return \Spryker\Shared\Router\Cache\CacheInterface
     */
    public function createMerchantPortalCacheWarmer(): CacheInterface
    {
        return new MerchantPortalCacheWarmer(
            $this->createMerchantPortalChainRouter(),
            $this->createFilesystem(),
            $this->getConfig()
        );
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface>
     */
    public function getBackendGatewayRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_BACKEND_GATEWAY_ROUTER_ENHANCER);
    }

    /**
     * @return \Spryker\Shared\Router\Cache\CacheInterface
     */
    public function createBackendGatewayCacheWarmer(): CacheInterface
    {
        return new BackendGatewayCacheWarmer(
            $this->createBackendGatewayChainRouter(),
            $this->createFilesystem(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createBackendApiChainRouter(): ChainRouter
    {
        return new ChainRouter($this->getBackendApiRouterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getBackendApiRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::PLUGINS_BACKEND_API_ROUTER);
    }

    /**
     * @return \Spryker\Zed\Router\Business\Loader\LoaderInterface
     */
    public function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::createBackofficeChainRouter()} instead.
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function createRouter(): ChainRouter
    {
        return new ChainRouter($this->getRouterPlugins());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::getBackofficeRouterPlugins()} instead.
     *
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::createBackofficeRouter()} instead.
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function createZedRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getRouterConfiguration(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::getBackofficeRouterEnhancerPlugins()} instead.
     *
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface>
     */
    public function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
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
            $this->getConfig()->getDevelopmentRouterConfiguration(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::createBackofficeCacheWarmer()} instead.
     *
     * @return \Spryker\Shared\Router\Cache\CacheInterface
     */
    public function createCache(): CacheInterface
    {
        return new Cache($this->createRouter(), $this->getConfig());
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterBusinessFactory::createBackofficeRouterResource()} instead.
     *
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    public function createResource(): ResourceInterface
    {
        return new RouterResource(
            $this->getConfig(),
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    public function createFilesystem(): Filesystem
    {
        return new Filesystem();
    }
}
