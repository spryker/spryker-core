<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class TagCloudRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_TAG_CLOUD;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderTagCloudResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $tagCloudRequestTransfer = $quoteTransfer->getFactFinderTagCloudRequest();

        $tagCloudAdapter = $this->ffConnector->createTagCloudAdapter();

        $this->logInfo($quoteTransfer, $tagCloudAdapter);

        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createTagCloudResponseConverter($tagCloudAdapter)
            ->convert();

        return $responseTransfer;
    }

}
