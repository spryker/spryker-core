<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\ApiConstants;

class SuggestRequest extends AbstractRequest implements RequestInterface
{
    
    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SUGGEST;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FfSuggestResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $suggestRequestTransfer = $quoteTransfer->getFfSuggestRequest();

        // @todo @Artem : check do we need send request? 
        // $request = mapper->map($searchRequestTransfer);
        $suggestAdapter = $this->ffConnector->createSuggestAdapter();
        // @todo check
        $suggestAdapter->getRawSuggestions();

        $this->logInfo($quoteTransfer, $suggestAdapter);
        
        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSuggestResponseConverter($suggestAdapter)
            ->convert();

        return $responseTransfer;
    }

}
