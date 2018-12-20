<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;
use Propel\Runtime\Collection\ObjectCollection;

class SalesReclamationMapper implements SalesReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $salesReclamationEntity
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function mapReclamationTransferToEntity(
        ReclamationTransfer $reclamationTransfer,
        SpySalesReclamation $salesReclamationEntity
    ): SpySalesReclamation {
        $salesReclamationEntity->fromArray($reclamationTransfer->toArray());
        $salesReclamationEntity->setNew($this->isReclamationNew($reclamationTransfer));

        $orderTransfer = $reclamationTransfer->getOrder();

        if ($orderTransfer && $orderTransfer->getIdSalesOrder()) {
            $salesReclamationEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        }

        return $salesReclamationEntity;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $reclamationEntity
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapReclamationEntityToTransfer(
        SpySalesReclamation $reclamationEntity,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($reclamationEntity->getOrder()->toArray(), true);

        $reclamationTransfer
            ->fromArray($reclamationEntity->toArray(), true)
            ->setOrder($orderTransfer);

        $reclamationTransfer = $this->addReclamationItemsToReclamationTransfer($reclamationEntity, $reclamationTransfer);
        $reclamationTransfer = $this->addCreatedOrdersToReclamationTransfer($reclamationEntity, $reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $salesReclamationItemEntity
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem
     */
    public function mapReclamationItemTransferToEntity(
        ReclamationItemTransfer $reclamationItemTransfer,
        SpySalesReclamationItem $salesReclamationItemEntity
    ): SpySalesReclamationItem {
        $salesReclamationItemEntity->fromArray($reclamationItemTransfer->toArray());
        $salesReclamationItemEntity->setNew($this->isReclamationItemNew($reclamationItemTransfer));

        $itemTransfer = $reclamationItemTransfer->getOrderItem();

        if ($itemTransfer && $itemTransfer->getIdSalesOrderItem()) {
            $salesReclamationItemEntity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
        }

        return $salesReclamationItemEntity;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $reclamationItemEntity
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapReclamationItemEntityToTransfer(
        SpySalesReclamationItem $reclamationItemEntity,
        ReclamationItemTransfer $reclamationItemTransfer
    ): ReclamationItemTransfer {
        $reclamationItemTransfer->fromArray($reclamationItemEntity->toArray(), true);

        $orderTransfer = new ItemTransfer();
        $orderTransfer->fromArray($reclamationItemEntity->getOrderItem()->toArray(), true);

        $reclamationItemTransfer->setOrderItem($orderTransfer);

        return $reclamationItemTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapEntityToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->fromArray($orderEntity->toArray(), true);

        return $orderTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderEntities
     * @param \Generated\Shared\Transfer\OrderCollectionTransfer $createdOrdersCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function mapSalesOrdersToOrderCollectionTransfer(
        ObjectCollection $salesOrderEntities,
        OrderCollectionTransfer $createdOrdersCollectionTransfer
    ): OrderCollectionTransfer {
        if (!$salesOrderEntities->count()) {
            return $createdOrdersCollectionTransfer;
        }

        foreach ($salesOrderEntities as $salesOrderEntitie) {
            $salesOrderTransfer = $this->mapEntityToOrderTransfer($salesOrderEntitie, new OrderTransfer());
            $createdOrdersCollectionTransfer->addOrder($salesOrderTransfer);
        }

        return $createdOrdersCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return bool
     */
    protected function isReclamationNew(ReclamationTransfer $reclamationTransfer): bool
    {
        return $reclamationTransfer->getIdSalesReclamation() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return bool
     */
    protected function isReclamationItemNew(ReclamationItemTransfer $reclamationItemTransfer): bool
    {
        return $reclamationItemTransfer->getIdSalesReclamationItem() === null;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $salesReclamationEntity
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addReclamationItemsToReclamationTransfer(
        SpySalesReclamation $salesReclamationEntity,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        $salesReclamationItemEntities = $salesReclamationEntity->getSpySalesReclamationItems();

        foreach ($salesReclamationItemEntities as $salesReclamationItemEntity) {
            $reclamationItemTransfer = $this->mapReclamationItemEntityToTransfer($salesReclamationItemEntity, new ReclamationItemTransfer());
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $salesReclamationEntity
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addCreatedOrdersToReclamationTransfer(
        SpySalesReclamation $salesReclamationEntity,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        $createdOrders = $salesReclamationEntity->getCreatedOrders();

        foreach ($createdOrders as $createdOrder) {
            $createdOrderTransfer = $this->mapEntityToOrderTransfer($createdOrder, new OrderTransfer());
            $reclamationTransfer->addOrder($createdOrderTransfer);
        }

        return $reclamationTransfer;
    }
}
