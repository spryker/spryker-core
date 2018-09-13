<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Generated\Shared\Transfer\SpySalesReclamationEntityTransfer;
use Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer;

class SalesReclamationMapper implements SalesReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer
     */
    public function mapReclamationTransferToEntityTransfer(
        ReclamationTransfer $reclamationTransfer
    ): SpySalesReclamationEntityTransfer {
        $reclamationEntityTransfer = new SpySalesReclamationEntityTransfer();
        $reclamationEntityTransfer->fromArray($reclamationTransfer->toArray(), true);

        $orderTransfer = $reclamationTransfer->getOrder();
        if ($orderTransfer && $orderTransfer->getIdSalesOrder()) {
            $reclamationEntityTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
            $reclamationEntityTransfer->setOrder(null);
        }

        $reclamationEntityTransfer->setState($reclamationTransfer->getState());

        return $reclamationEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface $reclamationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapEntityTransferToReclamationTransfer(
        SpySalesReclamationEntityTransfer $reclamationEntityTransfer
    ): ReclamationTransfer {
        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer
            ->fromArray($reclamationEntityTransfer->toArray(), true)
            ->setState($reclamationEntityTransfer->getState());

        $this->addReclamationItemsToReclamationTransfer($reclamationEntityTransfer, $reclamationTransfer);
        $this->addCreatedOrdersToReclamationTransfer($reclamationEntityTransfer, $reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer
     */
    public function mapReclamationItemTransferToEntityTransfer(
        ReclamationItemTransfer $reclamationItemTransfer
    ): SpySalesReclamationItemEntityTransfer {
        $reclamationItemEntityTransfer = new SpySalesReclamationItemEntityTransfer();
        $reclamationItemEntityTransfer->fromArray($reclamationItemTransfer->toArray(), true);

        $itemTransfer = $reclamationItemTransfer->getOrderItem();
        if ($itemTransfer && $itemTransfer->getIdSalesOrderItem()) {
            $reclamationItemEntityTransfer->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
            $reclamationItemEntityTransfer->setOrderItem(null);
        }

        $reclamationItemEntityTransfer->setState($reclamationItemTransfer->getState());

        return $reclamationItemEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer|\Spryker\Shared\Kernel\Transfer\EntityTransferInterface $reclamationItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapEntityTransferToReclamationItemTransfer(
        SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer
    ): ReclamationItemTransfer {
        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->fromArray($reclamationItemEntityTransfer->toArray(), true);
        $reclamationItemTransfer->setState($reclamationItemEntityTransfer->getState());

        return $reclamationItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $orderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapOrderEntityToOrderTransfer(SpySalesOrderEntityTransfer $orderEntityTransfer): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntityTransfer->toArray(), true);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer $reclamationEntityTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addReclamationItemsToReclamationTransfer(
        SpySalesReclamationEntityTransfer $reclamationEntityTransfer,
        ReclamationTransfer $reclamationTransfer
    ) {
        $reclamationItemsEntityTransfer = $reclamationEntityTransfer->getSpySalesReclamationItems();
        if ($reclamationItemsEntityTransfer->count()) {
            foreach ($reclamationItemsEntityTransfer as $reclamationItemEntityTransfer) {
                $reclamationItemTransfer = $this->mapEntityTransferToReclamationItemTransfer($reclamationItemEntityTransfer);
                $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
            }
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer $reclamationEntityTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addCreatedOrdersToReclamationTransfer(
        SpySalesReclamationEntityTransfer $reclamationEntityTransfer,
        ReclamationTransfer $reclamationTransfer
    ) {
        $createdOrders = $reclamationEntityTransfer->getSpySalesOrders();
        if ($createdOrders->count()) {
            foreach ($createdOrders as $orderEntityTransfer) {
                $createdOrderTransfer = $this->mapOrderEntityToOrderTransfer($orderEntityTransfer);
                $reclamationTransfer->addOrder($createdOrderTransfer);
            }
        }

        return $reclamationTransfer;
    }
}
