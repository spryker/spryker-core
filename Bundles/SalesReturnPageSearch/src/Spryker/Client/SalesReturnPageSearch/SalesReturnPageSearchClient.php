<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnPageSearch;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SalesReturnPageSearch\SalesReturnPageSearchFactory getFactory()
 */
class SalesReturnPageSearchClient extends AbstractClient implements SalesReturnPageSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer[]
     */
    public function searchReturnReasons(ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer): array
    {
        $requestParameters = $returnReasonSearchRequestTransfer->getRequestParameters();

        $searchQuery = $this->getFactory()->createReturnReasonSearchQuery($returnReasonSearchRequestTransfer);
        $searchQuery = $this->getFactory()
            ->getSearchClient()
            ->expandQuery(
                $searchQuery,
                $this->getFactory()->getReturnReasonSearchQueryExpanderPlugins(),
                $requestParameters
            );

        $resultFormatters = $this->getFactory()->getReturnReasonSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
