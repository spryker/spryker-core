<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnSearch\Reader;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class ReturnReasonSearchReader implements ReturnReasonSearchReaderInterface
{
    /**
     * @var \Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected $returnReasonSearchQueryPlugin;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected $returnReasonSearchQueryExpanderPlugins;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected $returnReasonSearchResultFormatterPlugins;

    /**
     * @param \Spryker\Client\SalesReturnSearch\Dependency\Client\SalesReturnSearchToSearchClientInterface $searchClient
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $returnReasonSearchQueryPlugin
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[] $returnReasonSearchQueryExpanderPlugins
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $returnReasonSearchResultFormatterPlugins
     */
    public function __construct(
        SalesReturnSearchToSearchClientInterface $searchClient,
        QueryInterface $returnReasonSearchQueryPlugin,
        array $returnReasonSearchQueryExpanderPlugins,
        array $returnReasonSearchResultFormatterPlugins
    ) {
        $this->searchClient = $searchClient;
        $this->returnReasonSearchQueryPlugin = $returnReasonSearchQueryPlugin;
        $this->returnReasonSearchQueryExpanderPlugins = $returnReasonSearchQueryExpanderPlugins;
        $this->returnReasonSearchResultFormatterPlugins = $returnReasonSearchResultFormatterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return array
     */
    public function searchReturnReasons(ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer): array
    {
        $requestParameters = $returnReasonSearchRequestTransfer->getRequestParameters();

        $searchQuery = $this->searchClient->expandQuery(
            $this->returnReasonSearchQueryPlugin,
            $this->returnReasonSearchQueryExpanderPlugins,
            $requestParameters
        );

        return $this->searchClient->search(
            $searchQuery,
            $this->returnReasonSearchResultFormatterPlugins,
            $requestParameters
        );
    }
}
