<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\CatalogSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\CatalogSearchResultFormatterPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\PaginatedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\SortedResultFormatterPlugin;

class CatalogDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_SEARCH = 'search client';
    const CATALOG_SEARCH_QUERY_PLUGIN = 'catalog search query plugin';
    const CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS = 'catalog search query expander plugins';
    const CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS = 'catalog search result formatter plugins';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        $container[self::CATALOG_SEARCH_QUERY_PLUGIN] = function () {
            return $this->createCatalogSearchQueryPlugin();
        };

        $container[self::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->createCatalogSearchQueryExpanderPlugins();
        };

        $container[self::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->createCatalogSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createCatalogSearchQueryPlugin()
    {
        return new CatalogSearchQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchQueryExpanderPlugins()
    {
        // TODO: Return empty array after deprecated method is removed.
        return $this->createDefaultCatalogSearchQueryExpanderPlugins();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     *
     * @deprecated Plugins provided for BC reasons because they should be defined on project level only.
     */
    private function createDefaultCatalogSearchQueryExpanderPlugins()
    {
        return [
            new FacetQueryExpanderPlugin(),
            new SortedQueryExpanderPlugin(),
            new PaginatedQueryExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCatalogSearchResultFormatterPlugins()
    {
        // TODO: Return empty array after deprecated method is removed.
        return $this->createDefaultCatalogSearchResultFormatterPlugins();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     *
     * @deprecated Plugins provided for BC reasons because they should be defined on project level only.
     */
    private function createDefaultCatalogSearchResultFormatterPlugins()
    {
        return [
            new FacetResultFormatterPlugin(),
            new SortedResultFormatterPlugin(),
            new PaginatedResultFormatterPlugin(),
            new CatalogSearchResultFormatterPlugin(),
        ];
    }

}
