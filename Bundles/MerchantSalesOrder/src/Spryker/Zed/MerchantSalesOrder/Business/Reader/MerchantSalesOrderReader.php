<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface;

class MerchantSalesOrderReader implements MerchantSalesOrderReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface
     */
    protected $merchantSalesOrderRepository;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface[]
     */
    protected $merchantOrderExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface[]
     */
    protected $merchantOrderFilterPlugins;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     * @param \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface[] $merchantOrderFilterPlugins
     * @param \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface[] $merchantOrderExpanderPlugins
     */
    public function __construct(
        MerchantSalesOrderToSalesFacadeInterface $salesFacade,
        MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository,
        array $merchantOrderFilterPlugins,
        array $merchantOrderExpanderPlugins
    ) {
        $this->salesFacade = $salesFacade;
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
        $this->merchantOrderFilterPlugins = $merchantOrderFilterPlugins;
        $this->merchantOrderExpanderPlugins = $merchantOrderExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): ?MerchantOrderTransfer
    {
        $merchantOrderTransfer = $this->merchantSalesOrderRepository->findMerchantOrder($merchantOrderCriteriaTransfer);

        if (!$merchantOrderTransfer) {
            return null;
        }

        if ($merchantOrderCriteriaTransfer->getWithOrder()) {
            $merchantOrderTransfer = $this->addSalesOrder($merchantOrderTransfer, $merchantOrderCriteriaTransfer);
            $merchantOrderTransfer = $this->executeSalesOrderFilterPlugins($merchantOrderTransfer);
        }

        if ($merchantOrderCriteriaTransfer->getWithUniqueProductsCount()) {
            $merchantOrderTransfer->setUniqueProductsCount(
                $this->merchantSalesOrderRepository->getUniqueProductsCount($merchantOrderTransfer->getIdMerchantOrder())
            );
        }

        return $this->executeMerchantOrderExpanderPlugins($merchantOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function filterMerchantOrderExpenses(OrderTransfer $orderTransfer, MerchantOrderTransfer $merchantOrderTransfer): OrderTransfer
    {
        $expenseTransfers = new ArrayObject();

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
                continue;
            }

            $expenseTransfers->append($expenseTransfer);
        }

        return $orderTransfer->setExpenses($expenseTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function executeMerchantOrderExpanderPlugins(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        foreach ($this->merchantOrderExpanderPlugins as $merchantOrderExpanderPlugin) {
            $merchantOrderTransfer = $merchantOrderExpanderPlugin->expand($merchantOrderTransfer);
        }

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function executeSalesOrderFilterPlugins(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        foreach ($this->merchantOrderFilterPlugins as $merchantOrderFiilterPlugin) {
            $merchantOrderTransfer = $merchantOrderFiilterPlugin->filter($merchantOrderTransfer);
        }

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function addSalesOrder(
        MerchantOrderTransfer $merchantOrderTransfer,
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): MerchantOrderTransfer {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($merchantOrderTransfer->getIdOrder());
        if (!$orderTransfer) {
            return $merchantOrderTransfer;
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $itemTransfers = [];
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $itemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
            }
            foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
                $itemTransfer = $itemTransfers[$merchantOrderItemTransfer->getIdOrderItem()];
                $merchantOrderItemTransfer->setOrderItem($itemTransfer);
            }
            unset($itemTransfers);
        }

        $orderTransfer = $this->filterMerchantOrderItems($orderTransfer, $merchantOrderTransfer);
        $orderTransfer = $this->filterMerchantOrderExpenses($orderTransfer, $merchantOrderTransfer);

        $merchantOrderTransfer->setOrder($orderTransfer);
        $merchantOrderTransfer->setExpenses($orderTransfer->getExpenses());

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function filterMerchantOrderItems(OrderTransfer $orderTransfer, MerchantOrderTransfer $merchantOrderTransfer): OrderTransfer
    {
        $orderItemTransfers = new ArrayObject();

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $orderItemTransfers->append($merchantOrderItem->getOrderItem());
        }

        $orderTransfer->setItems($orderItemTransfers);

        return $orderTransfer;
    }
}
