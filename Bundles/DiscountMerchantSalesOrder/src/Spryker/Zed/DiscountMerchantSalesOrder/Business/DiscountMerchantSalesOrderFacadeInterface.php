<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface DiscountMerchantSalesOrderFacadeInterface
{
    /**
     * Specification:
     * - Filters out discounts in MerchantOrderTransfer.order that does not belongs to the current merchant order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function filterMerchantDiscounts(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;
}
