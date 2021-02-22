<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use ArrayObject;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface MerchantSalesReturnFacadeInterface
{
    /**
     * Specification:
     * @TODO update specifications
     * - Requires OrderTransfer.idSalesOrder.
     * - Requires OrderTransfer.orderReference.
     * - Requires OrderTransfer.items.
     * - Iterates through the order items of given order looking for merchant reference presence.
     * - Skips all the order items without merchant reference.
     * - Creates a new merchant order for each unique merchant reference found.
     * - Creates a new merchant order item for each order item with merchant reference and assign it to a merchant order accordingly.
     * - Creates a new merchant order totals and assign it to a merchant order accordingly.
     * - Returns a collection of merchant orders filled with merchant order items and merchant order totals.
     * - Executes MerchantOrderPostCreatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function prepareReturn(ReturnTransfer $returnTransfer): ReturnTransfer;

    /**
     * Specification:
     * - Iterates through the order items ensuring all have set the same merchant reference.
     * - Requires ItemTransfer.merchantReference for all items.
     * - Returns ReturnResponseTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturn(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer;
}
