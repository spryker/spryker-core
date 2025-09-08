<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\CmsPageSearch\Config\CmsPagePaginationConfigBuilder;
use Spryker\Client\CmsPageSearch\Config\CmsPageSortConfigBuilder;
use Spryker\Client\CmsPageSearch\Config\PaginationConfigBuilderInterface;
use Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface;
use Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridgeInterface;
use Spryker\Client\CmsPageSearch\SearchQueryResolver\SearchQueryResolver;
use Spryker\Client\CmsPageSearch\SearchQueryResolver\SearchQueryResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Shared\Kernel\StrategyResolver;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchFactory extends AbstractFactory
{
    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createCmsPageSearchQuery(string $searchString): QueryInterface
    {
        $searchQuery = $this->createSearchQueryResolver()->resolve();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        return $searchQuery;
    }

    /**
     * @return \Spryker\Client\CmsPageSearch\Dependency\Client\CmsPageSearchToSearchBridgeInterface
     */
    public function getSearchClient(): CmsPageSearchToSearchBridgeInterface
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @deprecated use {@link static::getCmsPageSearchQueryPlugins()} instead.
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getCmsPageSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGIN_CMS_PAGE_SEARCH_QUERY);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface>
     */
    public function getCmsPageSearchQueryPlugins(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\CmsPageSearch\SearchQueryResolver\SearchQueryResolverInterface
     */
    public function createSearchQueryResolver(): SearchQueryResolverInterface
    {
        return new SearchQueryResolver($this->getCmsPageSearchQueryPlugins(), $this->getCmsPageSearchQueryPlugin());
    }

    /**
     * @return \Spryker\Shared\Kernel\StrategyResolver<array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>>
     */
    public function createCmsPageSearchQueryExpanderPluginsStrategyResolver(): StrategyResolver
    {
        return new StrategyResolver([
            CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER);
            },
            CmsPageSearchConfig::SEARCH_STRATEGY_SEARCH_HTTP => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_SEARCH_HTTP_QUERY_EXPANDER);
            },
        ], CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
    }

    /**
     * @return \Spryker\Shared\Kernel\StrategyResolver<array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>>
     */
    public function createCmsPageSearchResultFormattersStrategyResolver(): StrategyResolver
    {
        return new StrategyResolver([
            CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER);
            },
            CmsPageSearchConfig::SEARCH_STRATEGY_SEARCH_HTTP => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_SEARCH_HTTP_RESULT_FORMATTER);
            },
        ], CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
    }

    /**
     * @return \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface
     */
    public function createSortConfigBuilder(): SortConfigBuilderInterface
    {
        $cmsPageSortConfigBuilder = new CmsPageSortConfigBuilder();
        $cmsPageSortConfigBuilder->addSort($this->getConfig()->getAscendingNameSortConfigTransfer());
        $cmsPageSortConfigBuilder->addSort($this->getConfig()->getDescendingNameSortConfigTransfer());

        return $cmsPageSortConfigBuilder;
    }

    /**
     * @return \Spryker\Client\CmsPageSearch\Config\PaginationConfigBuilderInterface
     */
    public function createPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        $cmsPaginationConfigBuilder = new CmsPagePaginationConfigBuilder();
        $cmsPaginationConfigBuilder->setPaginationConfigTransfer($this->getConfig()->getCmsPagePaginationConfigTransfer());

        return $cmsPaginationConfigBuilder;
    }

    /**
     * @return \Spryker\Shared\Kernel\StrategyResolver<array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>>
     */
    public function createCmsPageSearchCountQueryExpanderPluginsStrategyResolver(): StrategyResolver
    {
        return new StrategyResolver([
            CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_COUNT_QUERY_EXPANDER);
            },
            CmsPageSearchConfig::SEARCH_STRATEGY_SEARCH_HTTP => function () {
                return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_SEARCH_HTTP_COUNT_QUERY_EXPANDER);
            },
        ], CmsPageSearchConfig::SEARCH_STRATEGY_ELASTICSEARCH);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface>
     */
    public function getSearchResultCountPlugins(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_SEARCH_RESULT_COUNT);
    }
}
