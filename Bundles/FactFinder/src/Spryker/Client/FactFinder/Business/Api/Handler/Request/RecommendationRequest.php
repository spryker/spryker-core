<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class RecommendationRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_RECOMMENDATION;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderRecommendationResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $requestParameters = $this->ffConnector->createRequestParametersFromRequestParser();
        $this->ffConnector->setRequestParameters($requestParameters);

        $suggestAdapter = $this->ffConnector->createRecommendationAdapter();

        $rec = $suggestAdapter->getRecommendations();

        $this->logInfo($quoteTransfer, $suggestAdapter);

        $responseTransfer = $this->converterFactory
            ->createRecommendationResponseConverter($suggestAdapter)
            ->convert();

        return $responseTransfer;
    }

}
