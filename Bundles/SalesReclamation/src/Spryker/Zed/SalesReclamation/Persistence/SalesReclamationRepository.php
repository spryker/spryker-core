<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationPersistenceFactory getFactory()
 */
class SalesReclamationRepository extends AbstractRepository implements SalesReclamationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function findReclamationById(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer
    {
        $reclamationTransfer->requireIdSalesReclamation();

        $salesReclamationQuery = $this->getFactory()
            ->createSalesReclamationQuery()
            ->leftJoinWithSpySalesReclamationItem()
                ->useSpySalesReclamationItemQuery()
                ->leftJoinWithOrderItem()
            ->endUse()
            ->leftJoinWithOrder()
            ->filterByIdSalesReclamation($reclamationTransfer->getIdSalesReclamation());

        $spyReclamations = $salesReclamationQuery->find();

        if (!$spyReclamations->count()) {
            return null;
        }

        return $this->getMapper()->mapEntityToReclamationTransfer($spyReclamations[0]);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer|null
     */
    public function findReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ?ReclamationItemTransfer
    {
        $reclamationItemTransfer->requireIdSalesReclamation();

        $salesReclamationQuery = $this->getFactory()
            ->createSalesReclamationItemQuery()
            ->leftJoinWithOrderItem()
            ->filterByIdSalesReclamationItem($reclamationItemTransfer->getIdSalesReclamationItem());

        $spyReclamationItems = $salesReclamationQuery->find();

        if (!$spyReclamationItems->count()) {
            return null;
        }

        return $this->getMapper()->mapEntityToReclamationItemTransfer($spyReclamationItems[0]);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function findCreatedOrdersByReclamationId(ReclamationTransfer $reclamationTransfer): ?OrderCollectionTransfer
    {
        $reclamationTransfer->requireIdSalesReclamation();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        $spyCreatedSalesOrders = $salesOrderQuery->find();

        if (!$spyCreatedSalesOrders->count()) {
            return null;
        }

        return $this->getMapper()->mapSalesOrdersToOrderCollectionTransfer($spyCreatedSalesOrders);
    }

    /**
     * @return \ArrayObject|null
     */
    public function findReclamations(): ?ArrayObject
    {
        $salesReclamationQuery = $this->getFactory()
            ->createSalesReclamationQuery()
            ->leftJoinWithSpySalesReclamationItem()
                ->useSpySalesReclamationItemQuery()
                ->leftJoinWithOrderItem()
            ->endUse()
            ->leftJoinWithOrder();

        $spyReclamations = $salesReclamationQuery->find();

        if (!$spyReclamations->count()) {
            return null;
        }

        $reclamationTransfers = new ArrayObject();

        foreach ($spyReclamations as $spySalesReclamation) {
            $reclamationTransfers->append($this->getMapper()->mapEntityToReclamationTransfer($spySalesReclamation));
        }

        return $reclamationTransfers;
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
