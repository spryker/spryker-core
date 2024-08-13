<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\SalesPaymentMerchantSalesMerchantCommissionBusinessFactory getFactory()
 */
class SalesPaymentMerchantSalesMerchantCommissionFacade extends AbstractFacade implements SalesPaymentMerchantSalesMerchantCommissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        return $this->getFactory()
            ->createPayoutAmountCalculatorComposite()
            ->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutReverseAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        return $this->getFactory()
            ->createPayoutReverseAmountCalculatorComposite()
            ->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }
}
