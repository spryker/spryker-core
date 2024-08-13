<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Use this plugin to calculate the payout amount or reversal payout for the order item.
 * This plugin interface is the foundation for both payout and reverse payout calculations.
 * The payout reverse amount will be transformed to negative value.
 */
interface MerchantPayoutCalculatorPluginInterface
{
    /**
     * Specification:
     * - Calculates the payout amount for the order item.
     * - Calculates the reverse payout amount for the order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int;
}
