<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
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

        $salesReclamationQuery = $this->buildQueryFromCriteria($salesReclamationQuery);
        if (!$salesReclamationQuery->count()) {
            return null;
        }

        $spyReclamationsEntityTransfer = $salesReclamationQuery->find();
        $spyReclamationEntityTransfer = $spyReclamationsEntityTransfer[0];

        return $this->getMapper()->mapEntityTransferToReclamationTransfer($spyReclamationEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function findCreatedOrdersByReclamationId(ReclamationTransfer $reclamationTransfer): OrderCollectionTransfer
    {
        $reclamationTransfer->requireIdSalesReclamation();
        $createdOrdersCollectionTransfer = new OrderCollectionTransfer();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        $createdOrdersEntityTransfer = $this->buildQueryFromCriteria($salesOrderQuery)->find();

        foreach ($createdOrdersEntityTransfer as $createdOrderEntity) {
            $createdOrdersCollectionTransfer->addOrder($createdOrderEntity);
        }

        return $createdOrdersCollectionTransfer;
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
