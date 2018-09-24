<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManager;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class ReclamationWriter implements ReclamationWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface
     */
    protected $salesReclamationEntityManager;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface $salesReclamationEntityManager
     */
    public function __construct(
        SalesReclamationEntityManagerInterface $salesReclamationEntityManager
    ) {
        $this->salesReclamationEntityManager = $salesReclamationEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(
        ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
    ): ?ReclamationTransfer {
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
        $reclamationTransfer->setState(SalesReclamationEntityManager::RECLAMATION_STATE_OPEN);
        $reclamationTransfer->setReclamationItems(new ArrayObject());

        $reclamationTransfer = $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);

        $orderItemsTransfer = $reclamationCreateRequestTransfer->getOrderItems();
        foreach ($orderItemsTransfer as $orderItemTransfer) {
            $reclamationItemTransfer = $this->addReclamationItem($orderItemTransfer);
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        $this->salesReclamationEntityManager->saveReclamationItems($reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function updateReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $reclamationTransfer->requireIdSalesReclamation();

        return $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);
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
        $reclamationItemTransfer->setState(SalesReclamationEntityManager::RECLAMATION_ITEM_STATE_OPEN);

        return $reclamationItemTransfer;
    }
}
