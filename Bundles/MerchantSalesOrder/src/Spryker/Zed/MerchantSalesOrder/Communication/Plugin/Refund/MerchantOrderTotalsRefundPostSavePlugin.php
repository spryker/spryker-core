<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Communication\Plugin\Refund;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 */
class MerchantOrderTotalsRefundPostSavePlugin extends AbstractPlugin implements RefundPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `OrderTransfer.merchantReferences` to be set.
     * - Does nothing if merchant references are not provided.
     * - Requires `OrderTransfer.idSalesOrder` to be provided.
     * - Requires `OrderTransfer.totals` to be provided.
     * - Finds merchant orders by provided references.
     * - Updates merchant order totals taken from `OrderTransfer.totals`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function postSave(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer): RefundTransfer
    {
        $this->getFacade()->updateMerchantOrderTotals($orderTransfer);

        return $refundTransfer;
    }
}
