<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

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

        $container[self::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->createCatalogSearchQueryExpanderPlugins();
        };

        $container[self::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->createCatalogSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchQueryExpanderPlugins()
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
        return [
            new FacetResultFormatterPlugin(),
            new SortedResultFormatterPlugin(),
            new PaginatedResultFormatterPlugin(),
        ];
    }

}
