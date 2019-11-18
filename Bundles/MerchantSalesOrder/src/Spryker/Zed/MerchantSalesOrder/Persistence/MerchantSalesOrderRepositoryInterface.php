<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantSalesOrderTransfer;

interface MerchantSalesOrderRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer|null
     */
    public function findOne(MerchantSalesOrderCriteriaFilterTransfer $merchantSalesOrderCriteriaFilterTransfer): ?MerchantSalesOrderTransfer;
}
