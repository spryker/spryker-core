<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Triggerer;

use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class OmsEventTriggerer implements OmsEventTriggererInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     */
    public function __construct(
        SalesReturnToOmsFacadeInterface $omsFacade,
        SalesReturnConfig $salesReturnConfig
    ) {
        $this->omsFacade = $omsFacade;
        $this->salesReturnConfig = $salesReturnConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return void
     */
    public function triggerOrderItemsReturnEvent(ReturnTransfer $returnTransfer): void
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($returnTransfer);

        $this->omsFacade->triggerEventForOrderItems($this->salesReturnConfig->getStartReturnEvent(), $salesOrderItemIds);
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
