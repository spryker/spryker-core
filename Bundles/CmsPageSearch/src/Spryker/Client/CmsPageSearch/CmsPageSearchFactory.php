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
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchFactory extends AbstractFactory
{
    /**
     * @param string $searchString
     * @param array $requestParameters
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $queryExpanderPlugins
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createCmsPageSearchQuery(
        string $searchString,
        array $requestParameters,
        array $queryExpanderPlugins
    ): QueryInterface {
        $searchQuery = $this->getCmsPageSearchQueryPlugin();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        $searchQuery = $this->getSearchClient()->expandQuery($searchQuery, $queryExpanderPlugins, $requestParameters);

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
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getCmsPageSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGIN_CMS_PAGE_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCmsPageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getCmsPageSearchResultFormatters(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_RESULT_FORMATTER);
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
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCmsPageSearchCountQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PLUGINS_CMS_PAGE_SEARCH_COUNT_QUERY_EXPANDER);
    }
}
