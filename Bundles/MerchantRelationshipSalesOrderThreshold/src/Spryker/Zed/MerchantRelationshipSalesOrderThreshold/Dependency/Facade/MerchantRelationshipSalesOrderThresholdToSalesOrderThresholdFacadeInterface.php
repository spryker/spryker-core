<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;

interface MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    public function isThresholdValid(
        SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function getSalesOrderThresholdTypeByKey(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer;
}
