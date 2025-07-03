<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service;

use Generated\Shared\Transfer\ItemTransfer;

interface SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemGroupKey(ItemTransfer $itemTransfer): string;
}
