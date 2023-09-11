<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\Listing\CatalogViewModePersistence;
use Spryker\Client\Catalog\PluginResolver\QueryExpanderPluginResolver;
use Spryker\Client\Catalog\PluginResolver\QueryExpanderPluginResolverInterface;
use Spryker\Client\Catalog\PluginResolver\QueryPluginResolver;
use Spryker\Client\Catalog\PluginResolver\QueryPluginResolverInterface;
use Spryker\Client\Catalog\PluginResolver\ResultFormatterPluginResolver;
use Spryker\Client\Catalog\PluginResolver\ResultFormatterPluginResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface as ExtensionQueryInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogConfig getConfig()
 */
class CatalogFactory extends AbstractFactory
{
    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
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
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createProductConcreteCatalogSearchQuery($searchString): QueryInterface
    {
        $searchQuery = $this->getProductConcreteCatalogSearchQueryPlugin();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        return $searchQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createSuggestSearchQuery($searchString)
    {
        $searchQuery = $this->getSuggestionSearchQueryPlugin();

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
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getCatalogSearchQueryPlugin()
    {
        return $this->createQueryPluginResolver()
            ->resolve(
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_PLUGIN),
            );
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getSuggestionSearchQueryPlugin(): QueryInterface
    {
        return $this->createQueryPluginResolver()
            ->resolve(
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SUGGESTION_QUERY_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_PLUGIN),
            );
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getProductConcreteCatalogSearchQueryPlugin()
    {
        return $this->createQueryPluginResolver()
            ->resolve(
                $this->getProvidedDependency(CatalogDependencyProvider::PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getCatalogSearchQueryExpanderPlugins(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createQueryExpanderPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_EXPANDER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getCatalogSearchCountQueryExpanderPlugins(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createQueryExpanderPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_COUNT_QUERY_EXPANDER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getSuggestionQueryExpanderPlugins(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createQueryExpanderPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_EXPANDER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_EXPANDER_PLUGINS),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getProductConcreteCatalogSearchQueryExpanderPlugins(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createQueryExpanderPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getCatalogSearchResultFormatters(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createResultFormatterPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSuggestionResultFormatters(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createResultFormatterPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_RESULT_FORMATTER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_RESULT_FORMATTER_PLUGINS),
            );
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryPlugin
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getProductConcreteCatalogSearchResultFormatters(ExtensionQueryInterface $queryPlugin): array
    {
        return $this->createResultFormatterPluginResolver()
            ->resolve(
                $queryPlugin,
                $this->getProvidedDependency(CatalogDependencyProvider::PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_VARIANTS),
                $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER),
            );
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getSuggestionQueryPlugin()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::SUGGESTION_QUERY_PLUGIN);
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface>
     */
    public function getFacetConfigTransferBuilderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @param string $type
     *
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface>
     */
    public function getFacetConfigTransferBuilderPluginVariants(string $type)
    {
        $pluginVariants = $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_FACET_CONFIG_TRANSFER_BUILDER_VARIANTS);

        if (isset($pluginVariants[$type])) {
            return $pluginVariants[$type];
        }

        return [];
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface>
     */
    public function getSortConfigTransferBuilderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getCatalogSearchCounterQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_CATALOG_SEARCH_COUNT_QUERY_EXPANDER);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getProductConcretePageSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGIN_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getProductConcretePageSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getProductConcretePageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_PRODUCT_CONCRETE_CATALOG_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface>
     */
    public function getSearchResultCountPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::PLUGINS_SEARCH_RESULT_COUNT);
    }

    /**
     * @return \Spryker\Client\Catalog\PluginResolver\QueryPluginResolverInterface
     */
    public function createQueryPluginResolver(): QueryPluginResolverInterface
    {
        return new QueryPluginResolver();
    }

    /**
     * @return \Spryker\Client\Catalog\PluginResolver\QueryExpanderPluginResolverInterface
     */
    public function createQueryExpanderPluginResolver(): QueryExpanderPluginResolverInterface
    {
        return new QueryExpanderPluginResolver();
    }

    /**
     * @return \Spryker\Client\Catalog\PluginResolver\ResultFormatterPluginResolverInterface
     */
    public function createResultFormatterPluginResolver(): ResultFormatterPluginResolverInterface
    {
        return new ResultFormatterPluginResolver();
    }
}
