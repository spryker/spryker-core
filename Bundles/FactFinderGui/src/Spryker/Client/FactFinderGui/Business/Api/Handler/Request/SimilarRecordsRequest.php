<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class SimilarRecordsRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SIMILAR_RECORDS;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSimilarRecordsResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $similarRecordsRequestTransfer = $quoteTransfer->getFactFinderSimilarRecordsRequest();

        $similarRecordsAdapter = $this->ffConnector->createSimilarRecordsAdapter();

        $this->logInfo($quoteTransfer, $similarRecordsAdapter);

        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSimilarRecordsResponseConverter($similarRecordsAdapter)
            ->convert();

        return $responseTransfer;
    }

}
