<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication;

use Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeBridge;
use Spryker\Glue\GlueBackendApiApplication\Exception\MissingRequestResourceFilterPluginException;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class GlueBackendApiApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

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
    public const PLUGINS_ROUTE_MATCHER = 'PLUGINS_ROUTE_MATCHER';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PLUGIN_REQUEST_RESOURCE_FILTER = 'PLUGIN_REQUEST_RESOURCE_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_ROUTER = 'PLUGINS_ROUTER';

    /**
     * @var string
     */
    public const PLUGINS_ROUTE_PROVIDER = 'PLUGINS_ROUTE_PROVIDER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addResourcePlugins($container);
        $container = $this->addRequestBuilderPlugins($container);
        $container = $this->addRequestValidatorPlugins($container);
        $container = $this->addRequestAfterRoutingValidatorPlugins($container);
        $container = $this->addResponseFormatterPlugins($container);
        $container = $this->addRouteMatcherPlugins($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addRouterPlugins($container);
        $container = $this->addRequestResourceFilterPlugin($container);
        $container = $this->addRouteProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function () {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new GlueBackendApiApplicationToStoreFacadeBridge($container->getLocator()->store()->facade());
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
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
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
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestBuilderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_BUILDER, function (Container $container) {
            return $this->getRequestBuilderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_VALIDATOR, function (Container $container) {
            return $this->getRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestAfterRoutingValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR, function (Container $container) {
            return $this->getRequestAfterRoutingValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addResponseFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESPONSE_FORMATTER, function (Container $container) {
            return $this->getResponseFormatterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function getRequestAfterRoutingValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRouteMatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTE_MATCHER, function () {
            return $this->getRouteMatcherPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface>
     */
    protected function getRouteMatcherPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRouterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTER, function () {
            return $this->getRouterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getRouterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRequestResourceFilterPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_REQUEST_RESOURCE_FILTER, function () {
            return $this->getRequestResourceFilterPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Glue\GlueBackendApiApplication\Exception\MissingRequestResourceFilterPluginException
     *
     * @return \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    public function getRequestResourceFilterPlugin(): RequestResourceFilterPluginInterface
    {
        throw new MissingRequestResourceFilterPluginException(
            sprintf(
                'There is no registered resource filter plugin.
                    Make sure that GlueBackendApiApplicationDependencyProvider::getRequestResourceFilterPlugin() returns
                    an implementation of %s',
                RequestResourceFilterPluginInterface::class,
            ),
        );
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addRouteProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTE_PROVIDER, function () {
            return $this->getRouteProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected function getRouteProviderPlugins(): array
    {
        return [];
    }
}
