<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface;

class ReclamationReader implements ReclamationReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface
     */
    protected $salesReclamationRepository;

    /**
     * @var \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface $salesReclamationRepository
     * @param \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesReclamationRepositoryInterface $salesReclamationRepository,
        SalesReclamationToSalesFacadeInterface $salesFacade
    ) {
        $this->salesReclamationRepository = $salesReclamationRepository;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @throws \Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException

     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $idSalesReclamation = $reclamationTransfer->getIdSalesReclamation();
        $reclamationTransfer = $this->salesReclamationRepository->findReclamationById($reclamationTransfer);

        if (!$reclamationTransfer) {
            throw new ReclamationNotFoundException(
                sprintf('There is no reclamation with id %s', $idSalesReclamation)
            );
        }

        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($reclamationTransfer->getOrder()->getIdSalesOrder());
        $reclamationTransfer->setOrder($orderTransfer);
        $reclamationTransfer->setReclamationItems($this->expandReclamationItems($reclamationTransfer));

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \ArrayObject
     */
    protected function expandReclamationItems(ReclamationTransfer $reclamationTransfer): ArrayObject
    {
        $reclamationItems = new ArrayObject();

        foreach ($reclamationTransfer->getReclamationItems() as $reclamationItemTransfer) {
            $itemTransfer = $this->findOrderItemById(
                $reclamationTransfer->getOrder(),
                $reclamationItemTransfer->getOrderItem()->getIdSalesOrderItem()
            );

            $reclamationItemTransfer->setOrderItem($itemTransfer);
            $reclamationItems->append($reclamationItemTransfer);
        }

        return $reclamationItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findOrderItemById(OrderTransfer $orderTransfer, int $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            if ($item->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $item;
            }
        }

        return null;
    }
}
