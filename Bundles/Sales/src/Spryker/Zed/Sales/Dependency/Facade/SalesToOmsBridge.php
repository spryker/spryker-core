<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class SalesToOmsBridge implements SalesToOmsInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity(): SpyOmsOrderItemState
    {
        return $this->omsFacade->getInitialStateEntity();
    }

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName): SpyOmsOrderProcess
    {
        return $this->omsFacade->getProcessEntity($processName);
    }

    /**
     * @return array
     */
    public function getOrderItemMatrix(): array
    {
        return $this->omsFacade->getOrderItemMatrix();
    }

    /**
     * @param int $idOrderItem
     *
     * @return array<string>
     */
    public function getManualEvents($idOrderItem): array
    {
        return $this->omsFacade->getManualEvents($idOrderItem);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag): array
    {
        return $this->omsFacade->getItemsWithFlag($order, $flag);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array<array<string>>
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder): array
    {
        return $this->omsFacade->getManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder): array
    {
        return $this->omsFacade->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getGroupedDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array
    {
        return $this->omsFacade->getGroupedDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idOrder
     *
     * @return bool
     */
    public function isOrderFlaggedExcludeFromCustomer($idOrder): bool
    {
        return $this->omsFacade->isOrderFlaggedExcludeFromCustomer($idOrder);
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = []): ?array
    {
        return $this->omsFacade->triggerEventForOrderItems($eventId, $orderItemIds, $data);
    }
}
