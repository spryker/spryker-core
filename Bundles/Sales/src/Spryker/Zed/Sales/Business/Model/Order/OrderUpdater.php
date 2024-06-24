<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderUpdater implements OrderUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected SalesConfig $salesConfig;

    /**
     * @var list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostUpdatePluginInterface>
     */
    protected array $orderPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostUpdatePluginInterface> $orderPostUpdatePlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesConfig $salesConfig,
        array $orderPostUpdatePlugins
    ) {
        $this->queryContainer = $queryContainer;
        $this->salesConfig = $salesConfig;
        $this->orderPostUpdatePlugins = $orderPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function update(OrderTransfer $orderTransfer, $idSalesOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idSalesOrder)
            ->findOne();

        if (!$orderEntity) {
            return false;
        }

        $this->hydrateEntityFromOrderTransfer($orderTransfer, $orderEntity);
        $orderEntity->save();

        $this->createOrderTotals($orderTransfer, $orderEntity);
        $this->updateOrderItems($orderTransfer, $orderEntity);
        $this->updateOrderExpenses($orderTransfer, $orderEntity);

        $this->executeOrderPostUpdatePlugins($orderTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function executeOrderPostUpdatePlugins(OrderTransfer $orderTransfer): void
    {
        foreach ($this->orderPostUpdatePlugins as $orderPostUpdatePlugin) {
            $orderPostUpdatePlugin->execute($orderTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function hydrateEntityFromOrderTransfer(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        $orderEntity->fromArray($orderTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function updateOrderItems(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        foreach ($orderEntity->getItems() as $salesOrderItemEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                if ($salesOrderItemEntity->getIdSalesOrderItem() !== $itemTransfer->getIdSalesOrderItem()) {
                    continue;
                }

                if ($this->salesConfig->shouldPersistModifiedOrderItemProperties()) {
                    $salesOrderItemEntity->fromArray($itemTransfer->modifiedToArray());
                }

                $salesOrderItemEntity->setCanceledAmount($itemTransfer->getCanceledAmount());
                $salesOrderItemEntity->setRefundableAmount($itemTransfer->getRefundableAmount());

                $salesOrderItemEntity->save();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function createOrderTotals(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        if (!$orderTransfer->getTotals()) {
            return;
        }

        $taxTotal = 0;
        if ($orderTransfer->getTotals()->getTaxTotal()) {
            $taxTotal = $orderTransfer->getTotals()->getTaxTotal()->getAmount();
        }

        $salesOrderTotalsEntity = new SpySalesOrderTotals();
        $salesOrderTotalsEntity->setFkSalesOrder($orderEntity->getIdSalesOrder());
        $salesOrderTotalsEntity->fromArray($orderTransfer->getTotals()->toArray());
        $salesOrderTotalsEntity->setTaxTotal($taxTotal);
        $salesOrderTotalsEntity->setCanceledTotal($orderTransfer->getTotals()->getCanceledTotal());
        $salesOrderTotalsEntity->setOrderExpenseTotal($orderTransfer->getTotals()->getExpenseTotal());
        $salesOrderTotalsEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function updateOrderExpenses(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
                if ($expenseTransfer->getIdSalesExpense() !== $expenseEntity->getIdSalesExpense()) {
                    continue;
                }

                $expenseEntity->setCanceledAmount($expenseTransfer->getCanceledAmount());
                $expenseEntity->setRefundableAmount($expenseTransfer->getRefundableAmount());

                $expenseEntity->save();
            }
        }
    }
}
