<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Deleter;

use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer;

interface SalesExpenseDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer
     */
    public function deleteSalesExpenseCollection(
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): SalesExpenseCollectionResponseTransfer;
}
