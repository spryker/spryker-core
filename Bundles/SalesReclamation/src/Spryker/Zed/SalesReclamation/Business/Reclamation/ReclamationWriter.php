<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

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
        $orderTransfer->setItems($reclamationCreateRequestTransfer->getOrder()->getItems());
        $reclamationTransfer = $this->reclamationMapper->mapOrderToReclamation($orderTransfer, new ReclamationTransfer());
        $reclamationTransfer->setState(SalesReclamationEntityManager::RECLAMATION_STATE_OPEN);
        $reclamationTransfer = $this->salesReclamationEntityManager->saveReclamation($reclamationTransfer);

        foreach ($reclamationTransfer->getReclamationItems() as $reclamationItemTransfer) {
            $reclamationItemTransfer->setFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        }

        $this->salesReclamationEntityManager->saveReclamationItems($reclamationTransfer);

        return $reclamationTransfer;
    }
}
