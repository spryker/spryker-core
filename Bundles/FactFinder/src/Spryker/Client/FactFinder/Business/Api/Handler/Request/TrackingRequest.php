<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use FACTFinder\Util\Parameters;
use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;
use Generated\Shared\Transfer\FactFinderTrackingResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class TrackingRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SEARCH;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderTrackingResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $trackingRequestTransfer = $quoteTransfer->getFactFinderTrackingRequest();

        $parameters = new Parameters();
        $parameters->setAll($this->getRequestData($trackingRequestTransfer));

        $this->ffConnector->setRequestParameters($parameters);

        $trackingAdapter = $this->ffConnector->createTrackingAdapter();
        $result = $trackingAdapter->applyTracking();

        $this->logInfo($quoteTransfer, $trackingAdapter);

        $responseTransfer = new FactFinderTrackingResponseTransfer();
        $responseTransfer->setResult($result);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer
     *
     * @return array
     */
    protected function getRequestData(FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer)
    {
        $data = $factFinderTrackingRequestTransfer->toArray();

        foreach ($data as $key => $value) {
            $newKey = str_replace('_', '', ucwords($key, '_'));
            $newKey = lcfirst($newKey);

            if ($newKey !== $key) {
                $data[$newKey] = $value;
                unset($data[$key]);
            }
        }

        return $data;
    }

}
