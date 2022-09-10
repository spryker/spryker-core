<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication;

use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication;
use Spryker\Glue\GlueBackendApiApplication\Cache\ControllerCacheCollector;
use Spryker\Glue\GlueBackendApiApplication\Cache\ControllerCacheCollectorInterface;
use Spryker\Glue\GlueBackendApiApplication\Collector\BackendScopeCollector;
use Spryker\Glue\GlueBackendApiApplication\Collector\BackendScopeCollectorInterface;
use Spryker\Glue\GlueBackendApiApplication\Dependency\External\GlueBackendApiApplicationToYamlAdapterInterface;
use Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface;
use Spryker\Glue\GlueBackendApiApplication\Expander\ContextExpanderInterface;
use Spryker\Glue\GlueBackendApiApplication\Expander\CustomRoutesContextExpander;
use Spryker\Glue\GlueBackendApiApplication\Expander\ResourcesContextExpander;
use Spryker\Glue\GlueBackendApiApplication\Finder\BackendScopeFinder;
use Spryker\Glue\GlueBackendApiApplication\Finder\BackendScopeFinderInterface;
use Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiation;
use Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueBackendApiApplication\RequestBuilder\LocaleRequestBuilder;
use Spryker\Glue\GlueBackendApiApplication\RequestBuilder\LocaleRequestBuilderInterface;
use Spryker\Glue\GlueBackendApiApplication\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueBackendApiApplication\RequestValidator\RequestValidatorInterface;
use Spryker\Glue\GlueBackendApiApplication\RequestValidator\ScopeRequestAfterRoutingValidator;
use Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder\ResourceRouteBuilder;
use Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder\ResourceRouteBuilderInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class GlueBackendApiApplicationFactory extends AbstractFactory
{
    /**
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createGlueBackendApiApplication(): ApplicationInterface
    {
        static $applicationCache = null;

        return $applicationCache ?: $applicationCache = new GlueBackendApiApplication(
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
     * @return \Spryker\Glue\GlueBackendApiApplication\RequestBuilder\LocaleRequestBuilderInterface
     */
    public function createLocaleRequestBuilder(): LocaleRequestBuilderInterface
    {
        return new LocaleRequestBuilder(
            $this->createLanguageNegotiation(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface
     */
    public function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreFacade(), $this->createLanguageNegotiator());
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface
     */
    public function getStoreFacade(): GlueBackendApiApplicationToStoreFacadeInterface
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    public function createLanguageNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function getResourcePlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_RESOURCE);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    public function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER);
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\RequestValidator\RequestValidatorInterface
     */
    public function createRequestCorsValidator(): RequestValidatorInterface
    {
        return new RequestCorsValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Collector\BackendScopeCollectorInterface
     */
    public function createBackendScopeCollector(): BackendScopeCollectorInterface
    {
        return new BackendScopeCollector(
            $this->getResourcePlugins(),
            $this->getRouteProviderPlugins(),
            $this->createRouteCollection(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\RequestValidator\RequestValidatorInterface
     */
    public function createScopeRequestAfterRoutingValidator(): RequestValidatorInterface
    {
        return new ScopeRequestAfterRoutingValidator();
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Dependency\External\GlueBackendApiApplicationToYamlAdapterInterface
     */
    public function getYamlAdapter(): GlueBackendApiApplicationToYamlAdapterInterface
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationDependencyProvider::ADAPTER_YAML);
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Finder\BackendScopeFinderInterface
     */
    public function createBackendScopeFinder(): BackendScopeFinderInterface
    {
        return new BackendScopeFinder(
            $this->getConfig(),
            $this->getYamlAdapter(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Expander\ContextExpanderInterface
     */
    public function createResourcesContextExpander(): ContextExpanderInterface
    {
        return new ResourcesContextExpander($this->getResourcePlugins());
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Expander\ContextExpanderInterface
     */
    public function createCustomRoutesContextExpander(): ContextExpanderInterface
    {
        return new CustomRoutesContextExpander($this->getRouteProviderPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Cache\ControllerCacheCollectorInterface
     */
    public function createControllerCacheCollector(): ControllerCacheCollectorInterface
    {
        return new ControllerCacheCollector(
            $this->getResourcePlugins(),
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
     * @return \Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder\ResourceRouteBuilderInterface
     */
    public function createResourceRouteBuilder(): ResourceRouteBuilderInterface
    {
        return new ResourceRouteBuilder();
    }
}
