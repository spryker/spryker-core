<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\CatalogSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\SuggestionQueryPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CatalogDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_SEARCH = 'search client';
    const CATALOG_SEARCH_QUERY_PLUGIN = 'catalog search query plugin';
    const CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS = 'catalog search query expander plugins';
    const CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS = 'catalog search result formatter plugins';
    const SUGGESTION_QUERY_PLUGIN = 'suggestion query plugin';
    const SUGGESTION_QUERY_EXPANDER_PLUGINS = 'suggestion query expander plugins';
    const SUGGESTION_RESULT_FORMATTER_PLUGINS = 'suggestion result formatter plugins';

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

        $container[self::SUGGESTION_QUERY_PLUGIN] = function () {
            return $this->createSuggestionQueryPlugin();
        };

        $container[self::SUGGESTION_QUERY_EXPANDER_PLUGINS] = function () {
            return $this->createSuggestionQueryExpanderPlugins();
        };

        $container[self::SUGGESTION_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->createSuggestionResultFormatterPlugins();
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
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCatalogSearchResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createSuggestionQueryPlugin()
    {
        return new SuggestionQueryPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createSuggestionQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createSuggestionResultFormatterPlugins()
    {
        return [];
    }
}
