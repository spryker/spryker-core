<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication;
use Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollector;
use Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollectorInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiation;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder\LocaleRequestBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder\LocaleRequestBuilderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\RequestCorsValidatorInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\Cache\Cache;
use Spryker\Glue\GlueStorefrontApiApplication\Router\Cache\CacheInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\ChainRouter;
use Spryker\Glue\GlueStorefrontApiApplication\Router\ChainRouterInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\Loader\ClosureLoader;
use Spryker\Glue\GlueStorefrontApiApplication\Router\Loader\LoaderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\RequestRoutingMatcher;
use Spryker\Glue\GlueStorefrontApiApplication\Router\RequestRoutingMatcherInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\Router;
use Spryker\Glue\GlueStorefrontApiApplication\Router\RouterInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource\RouterResource;
use Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource\RouterResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationFactory extends AbstractFactory
{
    /**
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_APPLICATIONS);
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createGlueStorefrontApiApplication(): ApplicationInterface
    {
        static $applicationCache = null;

        return $applicationCache ?: $applicationCache = new GlueStorefrontApiApplication(
            $this->createServiceContainer(),
            $this->getApplicationPlugins(),
        );
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder\LocaleRequestBuilderInterface
     */
    public function createLocaleRequestBuilder(): LocaleRequestBuilderInterface
    {
        return new LocaleRequestBuilder(
            $this->createLanguageNegotiation(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiationInterface
     */
    public function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreClient(), $this->createLanguageNegotiator());
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\ChainRouterInterface
     */
    public function createChainRouter(): ChainRouterInterface
    {
        return new ChainRouter(
            $this->getRouterPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\RequestRoutingMatcherInterface
     */
    public function createRequestRoutingMatcher(): RequestRoutingMatcherInterface
    {
        return new RequestRoutingMatcher(
            $this->createChainRouter(),
            $this->getRequestResourceFilterPlugin(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    public function getRequestResourceFilterPlugin(): RequestResourceFilterPluginInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGIN_REQUEST_RESOURCE_FILTER);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\RouterInterface
     */
    public function createRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createRouterResource(),
            $this->getConfig()->getRouterConfiguration(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\Loader\LoaderInterface
     */
    public function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource\RouterResourceInterface
     */
    public function createRouterResource(): RouterResourceInterface
    {
        return new RouterResource(
            $this->createRouteCollection(),
            $this->getRouteProviderPlugins(),
        );
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function createRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    public function createLanguageNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\Cache\CacheInterface
     */
    public function createCache(): CacheInterface
    {
        return new Cache($this->createChainRouter(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollectorInterface
     */
    public function createControllerCacheCollector(): ControllerCacheCollectorInterface
    {
        return new ControllerCacheCollector(
            $this->getResourcePlugins(),
            $this->getRouterPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    public function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouterPluginInterface>
     */
    public function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTER);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface
     */
    public function getStoreClient(): GlueStorefrontApiApplicationToStoreClientInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function getResourcePlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESOURCE);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface>
     */
    public function getRouteMatcherPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTE_MATCHER);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\RequestCorsValidatorInterface
     */
    public function createRequestCorsValidator(): RequestCorsValidatorInterface
    {
        return new RequestCorsValidator($this->getConfig());
    }
}
