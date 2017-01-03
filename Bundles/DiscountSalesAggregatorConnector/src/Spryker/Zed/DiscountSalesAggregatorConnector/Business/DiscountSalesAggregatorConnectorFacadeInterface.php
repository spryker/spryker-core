<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface DiscountSalesAggregatorConnectorFacadeInterface
{

    /**
     * Specification:
     *
     * - Loop over all quote items, and sum all calculated discounts
     * - Loop over all quote expenses, and sum all calculated discounts
     * - Store total amount into order transfer discount totals
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over item calculated discount and group by discount display name
     * - Loop over expense calculated discount and group by discount display name
     * - Store all variations to order transfer calculated discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over item calculated discounts and calculate item gross amount after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Take current grand total and subtract total discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over expenses and calculate order expense tax after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * - Loop over order expenses and calculated expense gross amount after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer);

}
