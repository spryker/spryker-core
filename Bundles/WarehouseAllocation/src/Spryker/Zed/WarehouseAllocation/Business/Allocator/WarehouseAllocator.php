<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Business\Allocator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer;
use Generated\Shared\Transfer\WarehouseAllocationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapperInterface;
use Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface;

class WarehouseAllocator implements WarehouseAllocatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface
     */
    protected WarehouseAllocationEntityManagerInterface $warehouseAllocationEntityManager;

    /**
     * @var \Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapperInterface
     */
    protected WarehouseAllocationOrderMapperInterface $warehouseAllocationOrderMapper;

    /**
     * @var list<\Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface>
     */
    protected array $salesOrderWarehouseAllocationPlugins;

    /**
     * @param \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface $warehouseAllocationEntityManager
     * @param \Spryker\Zed\WarehouseAllocation\Business\Mapper\WarehouseAllocationOrderMapperInterface $warehouseAllocationOrderMapper
     * @param list<\Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface> $salesOrderWarehouseAllocationPlugins
     */
    public function __construct(
        WarehouseAllocationEntityManagerInterface $warehouseAllocationEntityManager,
        WarehouseAllocationOrderMapperInterface $warehouseAllocationOrderMapper,
        array $salesOrderWarehouseAllocationPlugins
    ) {
        $this->warehouseAllocationEntityManager = $warehouseAllocationEntityManager;
        $this->warehouseAllocationOrderMapper = $warehouseAllocationOrderMapper;
        $this->salesOrderWarehouseAllocationPlugins = $salesOrderWarehouseAllocationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateWarehouses(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer = $this->executeSalesOrderWarehouseAllocationPlugins($orderTransfer);

        $warehouseAllocationCollectionTransfer = $this->warehouseAllocationOrderMapper->mapOrderTransferToWarehouseAllocationCollectionTransfer(
            $orderTransfer,
            new WarehouseAllocationCollectionTransfer(),
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($warehouseAllocationCollectionTransfer): void {
             $this->executeCreateWarehouseAllocationCollectionTransaction($warehouseAllocationCollectionTransfer);
        });

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer
     */
    protected function executeCreateWarehouseAllocationCollectionTransaction(
        WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
    ): WarehouseAllocationCollectionTransfer {
        foreach ($warehouseAllocationCollectionTransfer->getWarehouseAllocations() as $index => $warehouseAllocationTransfer) {
            if (!$this->isWarehouseDefinedForWarehouseAllocation($warehouseAllocationTransfer)) {
                continue;
            }

            $warehouseAllocationCollectionTransfer->getWarehouseAllocations()->offsetSet(
                $index,
                $this->warehouseAllocationEntityManager->createWarehouseAllocation($warehouseAllocationTransfer),
            );
        }

        return $warehouseAllocationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationTransfer $warehouseAllocationTransfer
     *
     * @return bool
     */
    protected function isWarehouseDefinedForWarehouseAllocation(WarehouseAllocationTransfer $warehouseAllocationTransfer): bool
    {
        return $warehouseAllocationTransfer->getWarehouse() && $warehouseAllocationTransfer->getWarehouseOrFail()->getIdStock();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeSalesOrderWarehouseAllocationPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->salesOrderWarehouseAllocationPlugins as $salesOrderWarehouseAllocationPlugin) {
            $orderTransfer = $salesOrderWarehouseAllocationPlugin->allocateWarehouse($orderTransfer);
        }

        return $orderTransfer;
    }
}
