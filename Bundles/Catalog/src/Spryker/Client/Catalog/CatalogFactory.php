<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\Listing\CatalogViewModePersistence;
use Spryker\Client\Catalog\ProductConcreteReader\ProductConcreteReader;
use Spryker\Client\Catalog\ProductConcreteReader\ProductConcreteReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogConfig getConfig()
 */
class CatalogFactory extends AbstractFactory
{
    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createCatalogSearchQuery($searchString)
    {
        $searchQuery = $this->getCatalogSearchQueryPlugin();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        return $searchQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSuggestSearchQuery($searchString)
    {
        $searchQuery = $this->getSuggestionQueryPlugin();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        return $searchQuery;
    }

    /**
     * @return \Spryker\Client\Catalog\Listing\CatalogViewModePersistenceInterface
     */
    public function createCatalogViewModePersistence()
    {
        return new CatalogViewModePersistence();
    }

    /**
     * @return \Spryker\Client\Catalog\ProductConcreteReader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->getConfig(),
            $this->getSearchClient(),
            $this->getProductConcretePageSearchQueryPlugin(),
            $this->getProductConcretePageSearchQueryExpanderPlugins(),
            $this->getProductConcretePageSearchResultFormatterPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        $paginationConfigBuilder = $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_PAGINATION_CONFIG_BUILDER);
        $paginationConfigBuilder->setPagination($this->getConfig()->getPaginationConfig());

        return $paginationConfigBuilder;
    }

    /**
     * @return \Spryker\Client\Catalog\CatalogConfig
     */
    public function getCatalogConfig(): CatalogConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getCatalogSearchQueryPlugin()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCatalogSearchQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getCatalogSearchResultFormatters()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getSuggestionQueryPlugin()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getSuggestionQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getSuggestionResultFormatters()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface[]
     */
    public function getFacetConfigTransferBuilderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @return \Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface[]
     */
    public function getSortConfigTransferBuilderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCatalogSearchCounterQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getProductConcretePageSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getProductConcretePageSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getProductConcretePageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER);
    }
}
