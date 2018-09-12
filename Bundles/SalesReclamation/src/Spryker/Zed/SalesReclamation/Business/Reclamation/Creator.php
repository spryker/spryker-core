<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationItemTableMap;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;

class Creator implements CreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer
    {
        $reclamationCreateRequestTransfer
            ->requireOrder()
            ->requireOrderItems();

        $orderTransfer = $reclamationCreateRequestTransfer->getOrder();

        $orderTransfer
            ->requireIdSalesOrder()
            ->requireEmail()
            ->requireFirstName()
            ->requireLastName()
            ->requireSalutation();

        $salutation = $orderTransfer->getSalutation();

        $customer = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $orderTransfer->getFirstName(),
            $orderTransfer->getLastName()
        );

        $spySaleReclamation = new SpySalesReclamation();
        $spySaleReclamation->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $spySaleReclamation->setCustomerName($customer);
        $spySaleReclamation->setCustomerReference($orderTransfer->getCustomerReference());
        $spySaleReclamation->setCustomerEmail($orderTransfer->getEmail());
        $spySaleReclamation->setState(SpySalesReclamationTableMap::COL_STATE_OPEN);

        $spySaleReclamation->save();

        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setOrder($orderTransfer);
        $reclamationTransfer->setCustomerName($customer);
        $reclamationTransfer->setIdSalesReclamation($spySaleReclamation->getIdSalesReclamation());
        $reclamationTransfer->setStatus($spySaleReclamation->getState());

        $orderItemsTransfer = $reclamationCreateRequestTransfer->getOrderItems();
        foreach ($orderItemsTransfer as $orderItemTransfer) {
            $reclamationTransfer = $this->addReclamationItem(
                $spySaleReclamation,
                $orderItemTransfer,
                $reclamationTransfer
            );
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $spySaleReclamation
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function addReclamationItem(
        SpySalesReclamation $spySaleReclamation,
        ItemTransfer $orderItemTransfer,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        $orderItemTransfer->requireIdSalesOrderItem();

        $spySaleReclamationItem = new SpySalesReclamationItem();
        $spySaleReclamationItem->setReclamation($spySaleReclamation);
        $spySaleReclamationItem->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
        $spySaleReclamationItem->setState(SpySalesReclamationItemTableMap::COL_STATE_OPEN);

        $spySaleReclamationItem->save();

        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->setOrderItem($orderItemTransfer);
        $reclamationTransfer->addReclamationItem($reclamationItemTransfer);

        return $reclamationTransfer;
    }
}
