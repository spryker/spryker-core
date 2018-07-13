<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\OrderTransfer;

interface AmountSalesUnitHydrateOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountSalesUnit(OrderTransfer $orderTransfer): OrderTransfer;
}