<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollector as ShipmentDiscountWithoutMultiShipmentCollector;

class ShipmentDiscountCollector extends ShipmentDiscountWithoutMultiShipmentCollector
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $expenseTransfer = $itemTransfer->getShipment()->getExpense();

            $isSatisfied = $this->shipmentDiscountDecisionRule->isExpenseSatisfiedBy($itemTransfer, $expenseTransfer, $clauseTransfer);

            if ($isSatisfied) {
                $discountableItems[] = $this->createDiscountableItemTransfer($expenseTransfer, $quoteTransfer->getPriceMode());
            }
        }

        return $discountableItems;
    }
}
