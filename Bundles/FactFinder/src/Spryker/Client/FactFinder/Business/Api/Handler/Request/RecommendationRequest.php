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
        $recommendationRequestTransfer = $quoteTransfer->getFactFinderRecommendationRequest();

        $recommendationAdapter = $this->ffConnector->createRecommendationAdapter();

        $this->logInfo($quoteTransfer, $recommendationAdapter);

        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createRecommendationResponseConverter($recommendationAdapter)
            ->convert();

        return $responseTransfer;
    }

}
