<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Discount\Business\Voucher\VoucherCodeInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class SalesOrderDiscountCodeUpdater implements SalesOrderDiscountCodeUpdaterInterface
{
    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherCodeInterface $voucherCode
     */
    public function __construct(protected DiscountRepositoryInterface $discountRepository, protected VoucherCodeInterface $voucherCode)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function releaseSalesOrderDiscountCodesByQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrderOrFail();

        $salesDiscountCodes = $this->discountRepository->getUsedSalesDiscountCodesBySalesOrderIds([$idSalesOrder]);

        if ($salesDiscountCodes !== []) {
            $this->voucherCode->releaseUsedCodes($salesDiscountCodes);
        }
    }
}
