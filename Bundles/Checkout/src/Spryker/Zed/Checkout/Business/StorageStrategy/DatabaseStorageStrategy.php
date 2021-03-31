<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StorageStrategy;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig;
use Spryker\Zed\Quote\Business\QuoteFacade;

class DatabaseStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade
     */
    public function __construct(QuoteFacade $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
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
        $quoteTransfer->setOrderReference(
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
        );

        $this->quoteFacade->updateQuote($quoteTransfer);
    }
}
