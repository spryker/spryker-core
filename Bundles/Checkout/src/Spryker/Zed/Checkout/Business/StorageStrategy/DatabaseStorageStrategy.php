<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StorageStrategy;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig;

class DatabaseStorageStrategy implements StorageStrategyInterface
{
    public function __construct() {
        // TODO
    }

    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    public function updateQuote(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        // TODO: Implement updateQuote() method.
    }
}
