<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface;

class Hydrator implements HydratorInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface
     */
    protected $salesReclamationRepository;

    /**
     * @param \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface $salesReclamationRepository
     */
    public function __construct(
        SalesReclamationToSalesFacadeInterface $salesFacade,
        SalesReclamationRepositoryInterface $salesReclamationRepository
    ) {
        $this->salesFacade = $salesFacade;
        $this->salesReclamationRepository = $salesReclamationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function hydrateByIdReclamation(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer
    {
        $reclamationTransfer = $this->salesReclamationRepository->findReclamationById($reclamationTransfer);

        if (!$reclamationTransfer) {
            return null;
        }

        $createdOrderCollection = $this->salesReclamationRepository->findCreatedOrdersByReclamationId($reclamationTransfer);
        if ($createdOrderCollection) {
            $reclamationTransfer->setCreatedOrders($createdOrderCollection->getOrders());
        }

        $orderTransfer = $reclamationTransfer->getOrder();
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $reclamationTransfer->setOrder($orderTransfer);

        $reclamationItems = new ArrayObject();
        foreach ($reclamationTransfer->getReclamationItems() as $reclamationItemTransfer) {
            $itemTransfer = $this->getOrderItemById($orderTransfer, $reclamationItemTransfer->getOrderItem()->getIdSalesOrderItem());
            $reclamationItemTransfer->setOrderItem($itemTransfer);

            $reclamationItems->append($reclamationItemTransfer);
        }

        $reclamationTransfer->setReclamationItems($reclamationItems);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateByOrder(OrderTransfer $orderTransfer): ReclamationTransfer
    {
        $orderTransfer->requireItems();

        $reclamationTransfer = new ReclamationTransfer();

        $reclamationTransfer->setOrder($orderTransfer);
        $reclamationTransfer->setCustomerReference($orderTransfer->getCustomerReference());
        $reclamationTransfer->setCustomerEmail($orderTransfer->getEmail());

        /** @var \Generated\Shared\Transfer\ReclamationItemTransfer[]|\ArrayObject $reclamationItems */
        $reclamationItems = new ArrayObject();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $reclamationItemTransfer = new ReclamationItemTransfer();
            $reclamationItemTransfer->setOrderItem($itemTransfer);

            $reclamationItems->append($reclamationItemTransfer);
        }

        $reclamationTransfer->setReclamationItems($reclamationItems);

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getOrderItemById(OrderTransfer $orderTransfer, int $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $item) {
            if ($item->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $item;
            }
        }

        return null;
    }
}
