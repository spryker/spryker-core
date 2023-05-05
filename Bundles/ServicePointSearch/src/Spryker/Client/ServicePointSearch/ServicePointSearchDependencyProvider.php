<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientBridge;
use Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToStoreClientBridge;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointSearchQueryPlugin;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchConfig getConfig()
 */
class ServicePointSearchDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGIN_SERVICE_POINT_SEARCH_QUERY = 'PLUGIN_SERVICE_POINT_SEARCH_QUERY';

    /**
     * @var string
     */
    public const PLUGINS_SERVICE_POINT_SEARCH_RESULT_FORMATTER = 'PLUGINS_SERVICE_POINT_SEARCH_RESULT_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_SERVICE_POINT_SEARCH_QUERY_EXPANDER = 'PLUGINS_SERVICE_POINT_SEARCH_QUERY_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addSearchClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addServicePointSearchQueryPlugin($container);
        $container = $this->addServicePointSearchResultFormatterPlugins($container);
        $container = $this->addServicePointSearchQueryExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new ServicePointSearchToSearchClientBridge(
                $container->getLocator()->search()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ServicePointSearchToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServicePointSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SERVICE_POINT_SEARCH_QUERY, function () {
            return $this->createServicePointSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServicePointSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SERVICE_POINT_SEARCH_RESULT_FORMATTER, function () {
            return $this->getServicePointSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServicePointSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SERVICE_POINT_SEARCH_QUERY_EXPANDER, function () {
            return $this->getServicePointSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createServicePointSearchQueryPlugin(): QueryInterface
    {
        return new ServicePointSearchQueryPlugin();
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getServicePointSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getServicePointSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
