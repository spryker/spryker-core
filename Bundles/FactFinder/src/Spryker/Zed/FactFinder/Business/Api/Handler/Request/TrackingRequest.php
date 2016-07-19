<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\ApiConstants;

class TrackingRequest extends AbstractRequest implements RequestInterface
{
    
    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SEARCH;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FfTagCloudResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $trackingRequestTransfer = $quoteTransfer->getFfTrackingRequest();

        // @todo @Artem : check do we need send request? 
        // $request = mapper->map($searchRequestTransfer);
        $trackingAdapter = $this->ffConnector->createTrackingAdapter();
        // @todo check
        $trackingAdapter->doTrackingFromRequest();

        $this->logInfo($quoteTransfer, $trackingAdapter);
        
        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createTrackingResponseConverter($trackingAdapter)
            ->convert();

        return $responseTransfer;
    }

}
