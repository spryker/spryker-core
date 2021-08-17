<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Checker;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteProductConfigurationChecker implements QuoteProductConfigurationCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            if (!$productConfigurationInstanceTransfer->getIsComplete()) {
                return false;
            }
        }

        return true;
    }
}
