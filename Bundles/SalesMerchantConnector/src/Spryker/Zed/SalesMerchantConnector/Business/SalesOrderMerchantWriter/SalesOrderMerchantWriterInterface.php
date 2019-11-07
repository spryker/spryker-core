<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantWriter;

use Generated\Shared\Transfer\SalesOrderMerchantTransfer;

interface SalesOrderMerchantWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $salesOrderMerchantTransfer): ?SalesOrderMerchantTransfer;
}
