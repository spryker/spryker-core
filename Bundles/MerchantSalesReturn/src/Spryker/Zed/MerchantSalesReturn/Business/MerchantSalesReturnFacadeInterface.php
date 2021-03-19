<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface MerchantSalesReturnFacadeInterface
{
    /**
     * Specification:
     * - Requires `Return.returnItems.item.idSalesOrderItem` transfer field to be set.
     * - Takes the first `ReturnItemTransfer` of the `ReturnTransfer` and finds the related `ItemTransfer` in the database.
     * - Sets `ReturnTransfer.merchantReference` by using the data from the first `ItemTransfer`.
     * - Returns `ReturnTransfer` object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function preCreate(ReturnTransfer $returnTransfer): ReturnTransfer;

    /**
     * Specification:
     * - Iterates through the order items ensuring all have set the same merchant reference.
     * - Returns `ReturnResponseTransfer` containing a message in case of a failed validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer;
}
