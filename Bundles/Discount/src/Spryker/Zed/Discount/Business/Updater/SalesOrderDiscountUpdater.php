<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaverInterface;
use Spryker\Zed\Discount\Business\Deleter\SalesDiscountDeleterInterface;

class SalesOrderDiscountUpdater implements SalesOrderDiscountUpdaterInterface
{
    /**
     * @param \Spryker\Zed\Discount\Business\Deleter\SalesDiscountDeleterInterface $salesDiscountDeleter
     * @param \Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaverInterface $discountOrderSaver
     */
    public function __construct(protected SalesDiscountDeleterInterface $salesDiscountDeleter, protected DiscountOrderSaverInterface $discountOrderSaver)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesOrderDiscountsByQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrderOrFail();

        $this->salesDiscountDeleter->deleteSalesDiscountsBySalesOrderIds([$idSalesOrder]);

        $this->discountOrderSaver->saveOrderDiscounts($quoteTransfer, $saveOrderTransfer);
    }
}
