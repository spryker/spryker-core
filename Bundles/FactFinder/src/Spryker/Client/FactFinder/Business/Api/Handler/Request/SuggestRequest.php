<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class SuggestRequest extends AbstractRequest implements RequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SUGGEST;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $suggestRequestTransfer = $quoteTransfer->getFactFinderSuggestRequest();

        $suggestAdapter = $this->ffConnector->createSuggestAdapter();

        $this->logInfo($quoteTransfer, $suggestAdapter);

        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSuggestResponseConverter($suggestAdapter)
            ->convert();

        return $responseTransfer;
    }

}
