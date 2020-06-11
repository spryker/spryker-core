<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Triggerer;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\SalesConfig;

class OmsEventTriggerer implements OmsEventTriggererInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $salesConfig;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     */
    public function __construct(
        SalesToOmsInterface $omsFacade,
        SalesConfig $salesConfig
    ) {
        $this->omsFacade = $omsFacade;
        $this->salesConfig = $salesConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function triggerOrderItemsCancelEvent(OrderTransfer $orderTransfer): void
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($orderTransfer);

        $this->omsFacade->triggerEventForOrderItems($this->salesConfig->getCancelEvent(), $salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->requireIdSalesOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
