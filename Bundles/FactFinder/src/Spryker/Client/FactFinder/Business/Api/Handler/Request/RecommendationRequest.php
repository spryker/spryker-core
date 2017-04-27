<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class RecommendationRequest extends AbstractRequest implements RecommendationRequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_RECOMMENDATION;

    /**
     * @param \Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderRecommendationResponseTransfer
     */
    public function request(FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer)
    {
        $requestParameters = $this->factFinderConnector->createRequestParametersFromRequestParser();
        $this->factFinderConnector->setRequestParameters($requestParameters);

        $suggestAdapter = $this->factFinderConnector
            ->createRecommendationAdapter();

        $recommendations = $suggestAdapter->getRecommendations();

        $responseTransfer = $this->converterFactory
            ->createRecommendationResponseConverter($suggestAdapter)
            ->convert();

        return $responseTransfer;
    }

}
