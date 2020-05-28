<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface;
use Spryker\Client\SalesReturnSearch\PaginationConfigBuilder\PaginationConfigBuilderInterface;
use Spryker\Client\SalesReturnSearch\PaginationConfigBuilder\ReturnReasonSearchPaginationConfigBuilder;
use Spryker\Client\SalesReturnSearch\Reader\ReturnReasonSearchReader;
use Spryker\Client\SalesReturnSearch\Reader\ReturnReasonSearchReaderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 */
class SalesReturnSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SalesReturnSearch\Reader\ReturnReasonSearchReaderInterface
     */
    public function createReturnReasonSearchReader(): ReturnReasonSearchReaderInterface
    {
        return new ReturnReasonSearchReader(
            $this->getSearchClient(),
            $this->getReturnReasonSearchQueryPlugin(),
            $this->getReturnReasonSearchQueryExpanderPlugins(),
            $this->getReturnReasonSearchResultFormatterPlugins()
        );
    }

    /**
     * @return \Spryker\Client\SalesReturnSearch\PaginationConfigBuilder\PaginationConfigBuilderInterface
     */
    public function createReturnReasonSearchPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        $returnReasonSearchPaginationConfigBuilder = new ReturnReasonSearchPaginationConfigBuilder();
        $returnReasonSearchPaginationConfigBuilder->setPaginationConfigTransfer(
            $this->getConfig()->getReturnReasonSearchPaginationConfigTransfer()
        );

        return $returnReasonSearchPaginationConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface
     */
    public function getSearchClient(): SalesReturnSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getReturnReasonSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::PLUGIN_RETURN_REASON_SEARCH_QUERY);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getReturnReasonSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::PLUGINS_RETURN_REASON_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getReturnReasonSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::PLUGINS_RETURN_REASON_SEARCH_QUERY_EXPANDER);
    }
}
