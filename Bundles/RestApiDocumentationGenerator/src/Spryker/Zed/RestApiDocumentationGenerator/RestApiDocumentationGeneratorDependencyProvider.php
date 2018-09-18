<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToDoctrineInflectorAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyYamlAdapter;

class RestApiDocumentationGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS = 'PLUGIN_RESOURCE_ROUTE_PLUGINS_PROVIDERS';
    public const PLUGIN_RESOURCE_RELATIONSHIPS_COLLECTION_PROVIDER = 'PLUGIN_RESOURCE_RELATIONSHIPS_COLLECTION_PROVIDER';
    public const YAML_DUMPER = 'YAML_DUMPER';
    public const TEXT_INFLECTOR = 'TEXT_INFLECTOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addYamlDumper($container);
        $container = $this->addTextInflector($container);
        $container = $this->addResourceRoutePluginsProviderPlugins($container);
        $container = $this->addResourceRelationshipsCollectionProviderPlugin($container);

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
    protected function addTextInflector(Container $container): Container
    {
        $container[static::TEXT_INFLECTOR] = function () {
            return new RestApiDocumentationGeneratorToDoctrineInflectorAdapter();
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResourceRelationshipsCollectionProviderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_RESOURCE_RELATIONSHIPS_COLLECTION_PROVIDER] = function () {
            return $this->getResourceRelationshipsCollectionProviderPlugin();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected function getResourceRelationshipsCollectionProviderPlugin(): array
    {
        return [];
    }
}
