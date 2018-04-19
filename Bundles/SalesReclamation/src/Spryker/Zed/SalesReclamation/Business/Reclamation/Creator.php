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
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class Creator implements CreatorInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface
     */
    private $reclamationManager;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface $reclamationManager
     */
    public function __construct(
        SalesReclamationEntityManagerInterface $reclamationManager
    ) {
        $this->reclamationManager = $reclamationManager;
    }

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

        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setOrder($orderTransfer);
        $reclamationTransfer->setCustomerName($customer);
        $reclamationTransfer->setCustomerReference($orderTransfer->getCustomerReference());
        $reclamationTransfer->setCustomerEmail($orderTransfer->getEmail());
        $reclamationTransfer->setStatus(SpySalesReclamationTableMap::COL_STATE_OPEN);

        $orderItemsTransfer = $reclamationCreateRequestTransfer->getOrderItems();
        foreach ($orderItemsTransfer as $orderItemTransfer) {
            $reclamationItemTransfer = $this->addReclamationItem($orderItemTransfer);
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        $reclamationTransfer = $this->reclamationManager->saveReclamation($reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    protected function addReclamationItem(ItemTransfer $orderItemTransfer): ReclamationItemTransfer
    {
        $orderItemTransfer->requireIdSalesOrderItem();

        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->setOrderItem($orderItemTransfer);
        $reclamationItemTransfer->setStatus(SpySalesReclamationItemTableMap::COL_STATE_OPEN);

        return $reclamationItemTransfer;
    }
}
