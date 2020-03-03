<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Triggerer;

use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface;

class OmsEventTriggerer implements OmsEventTriggererInterface
{
    protected const EVENT_EXECUTE_RETURN = 'execute-return';

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface $omsFacade
     */
    public function __construct(SalesReturnToOmsFacadeInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return void
     */
    public function triggerExecuteReturnEvent(ReturnTransfer $returnTransfer): void
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($returnTransfer);

        $this->omsFacade->triggerEventForOrderItems(static::EVENT_EXECUTE_RETURN, $salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $this->assertReturnIemRequirements($returnItemTransfer);

            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     *
     * @return void
     */
    protected function assertReturnIemRequirements(ReturnItemTransfer $returnItemTransfer): void
    {
        $returnItemTransfer
            ->requireOrderItem()
            ->getOrderItem()
                ->requireIdSalesOrderItem();
    }
}
