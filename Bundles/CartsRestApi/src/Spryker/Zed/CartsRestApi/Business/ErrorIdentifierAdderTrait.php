<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;

trait ErrorIdentifierAdderTrait
{
    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addErrorIdentifiersToQuoteResponseErrors(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $quoteErrorTransfers = $quoteResponseTransfer->getErrors();
        if (!$quoteErrorTransfers->count()) {
            return $quoteResponseTransfer;
        }

        foreach ($quoteErrorTransfers as $quoteErrorTransfer) {
            if (!empty(CartsRestApiConfig::getErrorToErrorIdentifierMapping()[$quoteErrorTransfer->getMessage()])) {
                $quoteErrorTransfer->setErrorIdentifier(
                    CartsRestApiConfig::getErrorToErrorIdentifierMapping()[$quoteErrorTransfer->getMessage()]
                );
            }
        }

        return $quoteResponseTransfer;
    }
}
