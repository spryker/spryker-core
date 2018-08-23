<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyYamlAdapter;

class RestApiDocumentationGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS = 'PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS';
    public const YAML_DUMPER = 'YAML_DUMPER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addYamlDumper($container);
        $container = $this->addResourceRoutePluginsProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYamlDumper(Container $container): Container
    {
        $container[static::YAML_DUMPER] = function () {
            return new RestApiDocumentationGeneratorToSymfonyYamlAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResourceRoutePluginsProviderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS] = function () {
            return $this->getResourceRoutePluginsProviderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    protected function getResourceRoutePluginsProviderPlugins(): array
    {
        return [];
    }
}
