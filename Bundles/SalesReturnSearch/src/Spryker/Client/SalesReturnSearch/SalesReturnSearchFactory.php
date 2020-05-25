<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnSearch;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface;
use Spryker\Client\SalesReturnSearch\Plugin\Elasticsearch\Query\ReturnReasonSearchQueryPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SalesReturnSearchFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createReturnReasonSearchQuery(ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer): QueryInterface
    {
        return new ReturnReasonSearchQueryPlugin($returnReasonSearchRequestTransfer);
    }

    /**
     * @return \Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface
     */
    public function getSearchClient(): SalesReturnSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::CLIENT_SEARCH);
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
