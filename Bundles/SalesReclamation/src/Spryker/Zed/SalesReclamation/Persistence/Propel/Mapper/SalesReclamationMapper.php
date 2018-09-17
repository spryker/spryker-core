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
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function mapReclamationTransferToEntity(
        ReclamationTransfer $reclamationTransfer
    ): SpySalesReclamation {
        $spySalesReclamation = new SpySalesReclamation();
        $spySalesReclamation->fromArray($reclamationTransfer->toArray());

        $orderTransfer = $reclamationTransfer->getOrder();
        if ($orderTransfer && $orderTransfer->getIdSalesOrder()) {
            $spySalesReclamation->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        }

        return $spySalesReclamation;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $spySalesReclamation
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapEntityToReclamationTransfer(
        SpySalesReclamation $spySalesReclamation
    ): ReclamationTransfer {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($spySalesReclamation->getOrder()->toArray(), true);

        $reclamationTransfer = new ReclamationTransfer();

        $reclamationTransfer
            ->fromArray($spySalesReclamation->toArray(), true)
            ->setOrder($orderTransfer);

        $this->addReclamationItemsToReclamationTransfer($spySalesReclamation, $reclamationTransfer);
        $this->addCreatedOrdersToReclamationTransfer($spySalesReclamation, $reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem
     */
    public function mapReclamationItemTransferToEntity(
        ReclamationItemTransfer $reclamationItemTransfer
    ): SpySalesReclamationItem {
        $spySalesReclamationItem = new SpySalesReclamationItem();
        $spySalesReclamationItem->fromArray($reclamationItemTransfer->toArray());

        $itemTransfer = $reclamationItemTransfer->getOrderItem();
        if ($itemTransfer && $itemTransfer->getIdSalesOrderItem()) {
            $spySalesReclamationItem->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
        }

        return $spySalesReclamationItem;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $spySalesReclamationItem
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapEntityToReclamationItemTransfer(
        SpySalesReclamationItem $spySalesReclamationItem
    ): ReclamationItemTransfer {
        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->fromArray($spySalesReclamationItem->toArray(), true);

        $orderTransfer = new ItemTransfer();
        $orderTransfer->fromArray($spySalesReclamationItem->getOrderItem()->toArray(), true);

        $reclamationItemTransfer->setOrderItem($orderTransfer);

        return $reclamationItemTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapEntityToOrderTransfer(SpySalesOrder $spySalesOrder): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($spySalesOrder->toArray(), true);

        return $orderTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $spySalesOrders
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function mapSalesOrdersToOrderCollectionTransfer(ObjectCollection $spySalesOrders): OrderCollectionTransfer
    {
        $createdOrdersCollectionTransfer = new OrderCollectionTransfer();

        foreach ($spySalesOrders as $spySalesOrder) {
            $salesOrderTransfer = $this->mapEntityToOrderTransfer($spySalesOrder);
            $createdOrdersCollectionTransfer->addOrder($salesOrderTransfer);
        }

        return $createdOrdersCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $spySalesReclamation
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addReclamationItemsToReclamationTransfer(
        SpySalesReclamation $spySalesReclamation,
        ReclamationTransfer $reclamationTransfer
    ) {
        $spySalesReclamationItems = $spySalesReclamation->getSpySalesReclamationItems();
        if ($spySalesReclamationItems->count()) {
            foreach ($spySalesReclamationItems as $spySalesReclamationItem) {
                $reclamationItemTransfer = $this->mapEntityToReclamationItemTransfer($spySalesReclamationItem);
                $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
            }
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $spySalesReclamation
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addCreatedOrdersToReclamationTransfer(
        SpySalesReclamation $spySalesReclamation,
        ReclamationTransfer $reclamationTransfer
    ) {
        $createdOrders = $spySalesReclamation->getCreatedOrders();
        if ($createdOrders->count()) {
            foreach ($createdOrders as $createdOrder) {
                $createdOrderTransfer = $this->mapEntityToOrderTransfer($createdOrder);
                $reclamationTransfer->addOrder($createdOrderTransfer);
            }
        }

        return $reclamationTransfer;
    }
}
