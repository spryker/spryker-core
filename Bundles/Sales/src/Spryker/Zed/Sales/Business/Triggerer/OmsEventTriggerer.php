<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Triggerer;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;

class OmsEventTriggerer implements OmsEventTriggererInterface
{
    /**
     * @uses \Spryker\Zed\Oms\OmsConfig::EVENT_CANCEL
     */
    protected const EVENT_CANCEL = 'cancel';

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     */
    public function __construct(SalesToOmsInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function triggerOrderItemsCancelEvent(OrderTransfer $orderTransfer): void
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($orderTransfer);

        $this->omsFacade->triggerEventForOrderItems(static::EVENT_CANCEL, $salesOrderItemIds);
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
