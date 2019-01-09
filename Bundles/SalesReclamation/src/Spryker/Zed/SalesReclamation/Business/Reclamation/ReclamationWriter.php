<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class ReclamationWriter implements ReclamationWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface
     */
    protected $salesReclamationEntityManager;

    /**
     * @var \Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationMapperInterface
     */
    protected $reclamationMapper;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface $salesReclamationEntityManager
     * @param \Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationMapperInterface $reclamationMapper
     */
    public function __construct(
        SalesReclamationEntityManagerInterface $salesReclamationEntityManager,
        ReclamationMapperInterface $reclamationMapper
    ) {
        $this->salesReclamationEntityManager = $salesReclamationEntityManager;
        $this->reclamationMapper = $reclamationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(
        ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
    ): ReclamationTransfer {
        $this->assertReclamationCreateRequestRequiredAttributes($reclamationCreateRequestTransfer);

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
    public function closeReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $reclamationTransfer->requireIdSalesReclamation();

        $reclamationTransfer->setIsOpen(false);

        return $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return void
     */
    protected function assertReclamationCreateRequestRequiredAttributes(
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
        $orderTransfer->setItems($reclamationCreateRequestTransfer->getOrderItems());
        $reclamationTransfer = $this->reclamationMapper
            ->mapOrderTransferToReclamationTransfer($orderTransfer, new ReclamationTransfer());
        $reclamationTransfer->setIsOpen(true);
        $reclamationTransfer = $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);

        foreach ($reclamationTransfer->getReclamationItems() as $reclamationItemTransfer) {
            $reclamationItemTransfer->setFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        }

        $this->salesReclamationEntityManager->saveReclamationItems($reclamationTransfer);

        return $reclamationTransfer;
    }
}
