<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;

interface SalesMerchantConnectorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function findOne(SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer): ?SalesOrderMerchantTransfer;
}
