<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManager;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class ReclamationWriter implements ReclamationWriterInterface
{
    use TransactionTrait;

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
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(
        ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
    ): ReclamationTransfer {
        $this->assertRequiredAttributes($reclamationCreateRequestTransfer);

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($reclamationCreateRequestTransfer) {
                return $this->executeCreateReclamationTransaction($reclamationCreateRequestTransfer);
            }
        );
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
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function updateReclamationItem(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        return $this->salesReclamationEntityManager->saveReclamationItem($reclamationItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(
        ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
    ): void {
        $reclamationCreateRequestTransfer
            ->requireOrder()
            ->requireOrderItems();

        $reclamationCreateRequestTransfer
            ->getOrder()
            ->requireIdSalesOrder()
            ->requireEmail()
            ->requireFirstName()
            ->requireLastName()
            ->requireSalutation();
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function executeCreateReclamationTransaction(
        ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
    ): ReclamationTransfer {
        $orderTransfer = $reclamationCreateRequestTransfer->getOrder();
        $reclamationTransfer = $this->createReclamationFromOrder($orderTransfer);
        $reclamationTransfer = $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);

        $orderItemsTransfer = $reclamationCreateRequestTransfer->getOrderItems();
        foreach ($orderItemsTransfer as $orderItemTransfer) {
            $reclamationItemTransfer = $this->createReclamationItemTransferFromOrderItemTransfer($orderItemTransfer);
            $reclamationItemTransfer->setFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        $this->salesReclamationEntityManager->saveReclamationItems($reclamationTransfer);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    protected function createReclamationFromOrder(OrderTransfer $orderTransfer): ReclamationTransfer
    {
        return (new ReclamationTransfer())
            ->setOrder($orderTransfer)
            ->setCustomerName($this->getCustomerNameFromOrder($orderTransfer))
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setCustomerEmail($orderTransfer->getEmail())
            ->setState(SalesReclamationEntityManager::RECLAMATION_STATE_OPEN)
            ->setReclamationItems(new ArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    protected function createReclamationItemTransferFromOrderItemTransfer(
        ItemTransfer $orderItemTransfer
    ): ReclamationItemTransfer {
        $orderItemTransfer->requireIdSalesOrderItem();

        $reclamationItemTransfer = new ReclamationItemTransfer();
        $reclamationItemTransfer->setOrderItem($orderItemTransfer);
        $reclamationItemTransfer->setState(SalesReclamationEntityManager::RECLAMATION_ITEM_STATE_OPEN);

        return $reclamationItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getCustomerNameFromOrder(OrderTransfer $orderTransfer): string
    {
        $salutation = $orderTransfer->getSalutation();

        return sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $orderTransfer->getFirstName(),
            $orderTransfer->getLastName()
        );
    }
}
