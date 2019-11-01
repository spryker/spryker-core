<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Generated\Shared\Transfer\SalesOrderMerchantTransfer;

interface SalesMerchantConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $orderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $orderMerchantTransfer): SalesOrderMerchantTransfer;
}
