<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnPageSearch;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesReturnPageSearch\Dependency\Client\SalesReturnPageSearchToSearchClientInterface;
use Spryker\Client\SalesReturnPageSearch\Plugin\Elasticsearch\Query\ReturnReasonSearchQueryPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SalesReturnPageSearchFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createReturnReasonSearchQuery(
        ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
    ): QueryInterface {
        return new ReturnReasonSearchQueryPlugin($returnReasonSearchRequestTransfer);
    }

    /**
     * @return \Spryker\Client\SalesReturnPageSearch\Dependency\Client\SalesReturnPageSearchToSearchClientInterface
     */
    public function getSearchClient(): SalesReturnPageSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getReturnReasonSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::PLUGINS_RETURN_REASON_SEARCH_RESULT_FORMATTER);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getReturnReasonSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::PLUGINS_RETURN_REASON_SEARCH_QUERY_EXPANDER);
    }
}
