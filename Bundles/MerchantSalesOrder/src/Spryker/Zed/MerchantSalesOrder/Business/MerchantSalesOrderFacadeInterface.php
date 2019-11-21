<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business;

use Generated\Shared\Transfer\MerchantSalesOrderTransfer;

interface MerchantSalesOrderFacadeInterface
{
    /**
     * Specification:
     * - Looks up a relation between an order and a merchant.
     * - If the relation doesn't exist, the method creates it and saves the data to `spy_merchant_sales_order`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    public function createMerchantSalesOrder(MerchantSalesOrderTransfer $merchantSalesOrderTransfer): MerchantSalesOrderTransfer;
}
