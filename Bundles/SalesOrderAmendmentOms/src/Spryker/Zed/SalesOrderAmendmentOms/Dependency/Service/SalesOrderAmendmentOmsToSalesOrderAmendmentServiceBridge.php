<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service;

use Generated\Shared\Transfer\ItemTransfer;

class SalesOrderAmendmentOmsToSalesOrderAmendmentServiceBridge implements SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface
{
    /**
     * @var \Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface
     */
    protected $salesOrderAmendmentService;

    /**
     * @param \Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface $salesOrderAmendmentService
     */
    public function __construct($salesOrderAmendmentService)
    {
        $this->salesOrderAmendmentService = $salesOrderAmendmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemGroupKey(ItemTransfer $itemTransfer): string
    {
        return $this->salesOrderAmendmentService->buildOriginalSalesOrderItemGroupKey($itemTransfer);
    }
}
