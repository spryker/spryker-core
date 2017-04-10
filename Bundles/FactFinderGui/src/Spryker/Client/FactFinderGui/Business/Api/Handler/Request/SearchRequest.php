<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class SearchRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SEARCH;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
//        $searchRequestTransfer = $quoteTransfer->getFactFinderSearchRequest();
//        $requestParameters = $this->ffConnector->createRequestParametersFromSearchRequestTransfer($searchRequestTransfer);
        $requestParameters = $this->ffConnector->createRequestParametersFromRequestParser();
        $this->ffConnector->setRequestParameters($requestParameters);

        $searchAdapter = $this->ffConnector->createSearchAdapter();


//        $searchAdapter->setQuery($searchRequestTransfer->getQuery());
//        $searchAdapter->setSid($_SERVER)


//        $status = $searchAdapter->getStatus();
//        $error = $searchAdapter->getError();

        $this->logInfo($quoteTransfer, $searchAdapter);

        // convert to FactFinderSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSearchResponseConverter($searchAdapter)
            ->convert();

        return $responseTransfer;
    }

}
