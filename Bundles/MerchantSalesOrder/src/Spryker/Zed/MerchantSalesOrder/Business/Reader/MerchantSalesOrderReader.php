<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeInterface;
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
     * @var array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface>
     */
    protected $merchantOrderExpanderPlugins;

    /**
     * @var array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface>
     */
    protected $merchantOrderFilterPlugins;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     * @param array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface> $merchantOrderFilterPlugins
     * @param array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface> $merchantOrderExpanderPlugins
     * @param \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantSalesOrderToSalesFacadeInterface $salesFacade,
        MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository,
        array $merchantOrderFilterPlugins,
        array $merchantOrderExpanderPlugins,
        MerchantSalesOrderToMerchantFacadeInterface $merchantFacade
    ) {
        $this->salesFacade = $salesFacade;
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
        $this->merchantOrderFilterPlugins = $merchantOrderFilterPlugins;
        $this->merchantOrderExpanderPlugins = $merchantOrderExpanderPlugins;
        $this->merchantFacade = $merchantFacade;
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
            /** @var int $idMerchantOrder */
            $idMerchantOrder = $merchantOrderTransfer->getIdMerchantOrder();

            $merchantOrderTransfer->setUniqueProductsCount(
                $this->merchantSalesOrderRepository->getUniqueProductsCount($idMerchantOrder),
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
        foreach ($this->merchantOrderFilterPlugins as $merchantOrderFilterPlugin) {
            $merchantOrderTransfer = $merchantOrderFilterPlugin->filter($merchantOrderTransfer);
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
        /** @var int $idOrder */
        $idOrder = $merchantOrderTransfer->getIdOrder();

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idOrder);
        if (!$orderTransfer) {
            return $merchantOrderTransfer;
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $itemTransfers = [];
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                /** @var int $idSalesOrderItem */
                $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

                $itemTransfers[$idSalesOrderItem] = $itemTransfer;
            }
            foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
                /** @var int $idOrderItem */
                $idOrderItem = $merchantOrderItemTransfer->getIdOrderItem();

                $itemTransfer = $itemTransfers[$idOrderItem];
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

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderCollection(
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantOrderCollectionTransfer = $this->merchantSalesOrderRepository
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        if ($merchantOrderCriteriaTransfer->getWithMerchant()) {
            $merchantOrderCollectionTransfer = $this->addMerchantToMerchantOrders($merchantOrderCollectionTransfer);
        }

        if ($merchantOrderCriteriaTransfer->getWithOrder()) {
            $merchantOrderCollectionTransfer = $this->expandMerchantOrdersWithSalesOrder(
                $merchantOrderCollectionTransfer,
                $merchantOrderCriteriaTransfer,
            );
        }

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    protected function expandMerchantOrdersWithSalesOrder(
        MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer,
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantOrderTransfersWithOrders = new ArrayObject();
        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            $merchantOrderTransfer = $this->addSalesOrder($merchantOrderTransfer, $merchantOrderCriteriaTransfer);
            $merchantOrderTransfer = $this->executeSalesOrderFilterPlugins($merchantOrderTransfer);
            $merchantOrderTransfersWithOrders->append($merchantOrderTransfer);
        }

        return $merchantOrderCollectionTransfer->setMerchantOrders($merchantOrderTransfersWithOrders);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    protected function addMerchantToMerchantOrders(
        MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantReferences = [];

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            if ($merchantOrderTransfer->getMerchantReference() !== null) {
                $merchantReferences[] = $merchantOrderTransfer->getMerchantReference();
            }
        }

        $merchantCollectionTransfer = $this->getMerchantsByMerchantReferences($merchantReferences);

        return $this->mapMerchantCollectionToMerchantOrderTransfers(
            $merchantCollectionTransfer,
            $merchantOrderCollectionTransfer,
        );
    }

    /**
     * @param array<string> $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function getMerchantsByMerchantReferences(array $merchantReferences): MerchantCollectionTransfer
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReferences($merchantReferences);

        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function mapMerchantCollectionToMerchantOrderTransfers(
        MerchantCollectionTransfer $merchantCollectionTransfer,
        MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantTransfers = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchant) {
            $merchantTransfers[$merchant->getMerchantReference()] = (new MerchantTransfer())
                ->fromArray($merchant->toArray(), true);
        }

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            if (isset($merchantTransfers[$merchantOrderTransfer->getMerchantReference()])) {
                $merchantOrderTransfer->setMerchant($merchantTransfers[$merchantOrderTransfer->getMerchantReference()]);
            }
        }

        return $merchantOrderCollectionTransfer;
    }
}
