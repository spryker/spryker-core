<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StorageStrategy;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToQuoteFacadeInterface;

class DatabaseStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToQuoteFacadeInterface $quoteFacade
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CheckoutToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function updateQuote(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return;
        }

        $quoteTransfer->setOrderReference(
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
        );

        $this->quoteFacade->updateQuote($quoteTransfer);
    }
}
