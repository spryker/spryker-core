<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesSplit\Business\SalesSplitBusinessFactory getFactory()
 */
class SalesSplitFacade extends AbstractFacade implements SalesSplitFacadeInterface
{
    /**
     * Splits sales order items which have a quantity > 1 into two parts. One part with the new given quantity and
     * the other part with the rest.
     *
     * Example:
     *   Item A with quantity = 100
     * Split(20)
     *   Item A with quantity = 80
     *   New Item B with quantity = 20
     *
     * Specification:
     * - Validate if split is possible. (Otherwise return $response->getSuccess() === false and add validation messages)
     * - Create a copy of the given order item with given quantity
     * - Decrement the quantity of the original given order item (including all options)
     * - Return $response->getSuccess() === true
     *
     * @api
     *
     * @param int $idSalesOrderItem
     * @param float $quantity
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->getFactory()->createOrderItemSplitter()->split($idSalesOrderItem, $quantity);
    }
}
