<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Deleter;

use Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer;

interface SalesDiscountDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer $salesDiscountCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteSalesDiscounts(
        SalesDiscountCollectionDeleteCriteriaTransfer $salesDiscountCollectionDeleteCriteriaTransfer
    ): void;

    /**
     * @param list<int> $salesOrderIds
     *
     * @return void
     */
    public function deleteSalesDiscountsBySalesOrderIds(array $salesOrderIds): void;
}
