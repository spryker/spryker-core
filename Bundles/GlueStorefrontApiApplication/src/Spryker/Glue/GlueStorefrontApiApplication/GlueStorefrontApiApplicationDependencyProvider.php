<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientBridge;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\External\GlueStorefrontApiApplicationToYamlAdapter;
use Spryker\Glue\GlueStorefrontApiApplication\Exception\MissingRequestResourceFilterPluginException;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_APPLICATIONS = 'PLUGINS_APPLICATIONS';

    /**
     * @var string
     */
    public const PLUGINS_RESOURCE = 'PLUGINS_RESOURCE';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_BUILDER = 'PLUGINS_REQUEST_BUILDER';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_VALIDATOR = 'PLUGINS_REQUEST_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR = 'PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_RESPONSE_FORMATTER = 'PLUGINS_RESPONSE_FORMATTER';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGINS_ROUTE_MATCHER = 'PLUGINS_ROUTE_MATCHER';

    /**
     * @var string
     */
    public const PLUGINS_ROUTER = 'PLUGINS_ROUTER';

    /**
     * @var string
     */
    public const PLUGINS_ROUTE_PROVIDER = 'PLUGINS_ROUTE_PROVIDER';

    /**
     * @var string
     */
    public const PLUGIN_REQUEST_RESOURCE_FILTER = 'PLUGIN_REQUEST_RESOURCE_FILTER';

    /**
     * @var string
     */
    public const ADAPTER_YAML = 'ADAPTER_YAML';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addResourcePlugins($container);
        $container = $this->addRequestBuilderPlugins($container);
        $container = $this->addRequestValidatorPlugins($container);
        $container = $this->addRequestAfterRoutingValidatorPlugins($container);
        $container = $this->addResponseFormatterPlugins($container);
        $container = $this->addStoreClient($container);
        $container = $this->addRouteMatcherPlugins($container);
        $container = $this->addRouterPlugins($container);
        $container = $this->addRequestResourceFilterPlugin($container);
        $container = $this->addRouteProviderPlugins($container);
        $container = $this->addYamlAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATIONS, function () {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourcePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESOURCE, function () {
            return $this->getResourcePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected function getResourcePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestBuilderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_BUILDER, function (Container $container) {
            return $this->getRequestBuilderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_VALIDATOR, function (Container $container) {
            return $this->getRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestAfterRoutingValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR, function (Container $container) {
            return $this->getRequestAfterRoutingValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResponseFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESPONSE_FORMATTER, function (Container $container) {
            return $this->getResponseFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new GlueStorefrontApiApplicationToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function getRequestAfterRoutingValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRouteMatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTE_MATCHER, function () {
            return $this->getRouteMatcherPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface>
     */
    protected function getRouteMatcherPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRouterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTER, function () {
            return $this->getRouterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getRouterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRequestResourceFilterPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_REQUEST_RESOURCE_FILTER, function () {
            return $this->getRequestResourceFilterPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Glue\GlueStorefrontApiApplication\Exception\MissingRequestResourceFilterPluginException
     *
     * @return \Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    public function getRequestResourceFilterPlugin(): RequestResourceFilterPluginInterface
    {
        throw new MissingRequestResourceFilterPluginException(
            sprintf(
                'There is no registered resource filter plugin.
                    Make sure that GlueStorefrontApiApplicationDependencyProvider::getRequestResourceFilterPlugin() returns
                    an implementation of %s',
                RequestResourceFilterPluginInterface::class,
            ),
        );
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRouteProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTE_PROVIDER, function () {
            return $this->getRouteProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addYamlAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_YAML, function () {
            return new GlueStorefrontApiApplicationToYamlAdapter();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected function getRouteProviderPlugins(): array
    {
        return [];
    }
}
