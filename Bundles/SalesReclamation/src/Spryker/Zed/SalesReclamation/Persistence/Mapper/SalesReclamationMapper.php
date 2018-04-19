<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Mapper;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
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

        $reclamationEntityTransfer->setState($reclamationTransfer->getStatus());

        return $reclamationEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer $reclamationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapEntityTransferToReclamationTransfer(
        SpySalesReclamationEntityTransfer $reclamationEntityTransfer
    ): ReclamationTransfer {
        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->fromArray($reclamationEntityTransfer->toArray(), true);
        $reclamationTransfer->setStatus($reclamationEntityTransfer->getState());

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

        $reclamationItemEntityTransfer->setState($reclamationItemTransfer->getStatus());

        return $reclamationItemEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapEntityTransferToReclamationItemTransfer(
        SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer
    ): ReclamationItemTransfer {
        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->fromArray($reclamationItemEntityTransfer->toArray(), true);
        $reclamationItemTransfer->setId($reclamationItemEntityTransfer->getIdSalesReclamationItem());
        $reclamationItemTransfer->setStatus($reclamationItemEntityTransfer->getState());

        return $reclamationItemTransfer;
    }
}
