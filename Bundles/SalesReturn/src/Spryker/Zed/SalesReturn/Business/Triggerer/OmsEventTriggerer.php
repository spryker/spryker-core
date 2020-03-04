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
    // TODO: get from shared config?
    protected const EVENT_RETURN = 'return';

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
    public function triggerOrderItemsReturnEvent(ReturnTransfer $returnTransfer): void
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($returnTransfer);

        $this->omsFacade->triggerEventForOrderItems(static::EVENT_RETURN, $salesOrderItemIds);
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
            $this->assertReturnItemRequirements($returnItemTransfer);

            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     *
     * @return void
     */
    protected function assertReturnItemRequirements(ReturnItemTransfer $returnItemTransfer): void
    {
        $returnItemTransfer
            ->requireOrderItem()
            ->getOrderItem()
                ->requireIdSalesOrderItem();
    }
}
