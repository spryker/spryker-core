<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ServicePointSearch\Builder\PaginationConfigBuilderInterface;
use Spryker\Client\ServicePointSearch\Builder\ServicePointSearchPaginationConfigBuilder;
use Spryker\Client\ServicePointSearch\Builder\ServicePointSearchSortConfigBuilder;
use Spryker\Client\ServicePointSearch\Builder\SortConfigBuilderInterface;
use Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientInterface;
use Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToStoreClientInterface;
use Spryker\Client\ServicePointSearch\Reader\ServicePointSearchReader;
use Spryker\Client\ServicePointSearch\Reader\ServicePointSearchReaderInterface;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchConfig getConfig()
 */
class ServicePointSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ServicePointSearch\Reader\ServicePointSearchReaderInterface
     */
    public function createServicePointSearchReader(): ServicePointSearchReaderInterface
    {
        return new ServicePointSearchReader(
            $this->getSearchClient(),
            $this->getServicePointSearchQueryPlugin(),
            $this->getServicePointSearchQueryExpanderPlugins(),
            $this->getServicePointSearchResultFormatterPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\Builder\PaginationConfigBuilderInterface
     */
    public function createServicePointSearchPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        $servicePointSearchPaginationConfigBuilder = new ServicePointSearchPaginationConfigBuilder();
        $servicePointSearchPaginationConfigBuilder->setPaginationConfigTransfer(
            $this->getConfig()->getServicePointSearchPaginationConfigTransfer(),
        );

        return $servicePointSearchPaginationConfigBuilder;
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\Builder\SortConfigBuilderInterface
     */
    public function createServicePointSearchSortConfigBuilder(): SortConfigBuilderInterface
    {
        return (new ServicePointSearchSortConfigBuilder())
            ->addSort($this->getConfig()->getAscendingCitySortConfigTransfer())
            ->addSort($this->getConfig()->getDescendingCitySortConfigTransfer());
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\ServicePointSearchConfig
     */
    public function getServicePointSearchConfig(): ServicePointSearchConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToSearchClientInterface
     */
    public function getSearchClient(): ServicePointSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\Dependency\Client\ServicePointSearchToStoreClientInterface
     */
    public function getStoreClient(): ServicePointSearchToStoreClientInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getServicePointSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::PLUGIN_SERVICE_POINT_SEARCH_QUERY);
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getServicePointSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::PLUGINS_SERVICE_POINT_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getServicePointSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::PLUGINS_SERVICE_POINT_SEARCH_QUERY_EXPANDER);
    }
}
