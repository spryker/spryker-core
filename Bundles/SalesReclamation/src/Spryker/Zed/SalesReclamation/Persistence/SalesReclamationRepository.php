<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesReclamation\Persistence\Mapper\SalesReclamationMapperInterface;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationPersistenceFactory getFactory()
 *
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

        $query = $this->getFactory()
            ->createSalesReclamationQuery()
            ->leftJoinWithSpySalesReclamationItem()
            ->useSpySalesReclamationItemQuery()
                ->leftJoinWithOrderItem()
            ->endUse()
            ->leftJoinWithOrder()
            ->filterByIdSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        /** @var \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer[] $spyReclamationsEntityTransfer */
        $spyReclamationsEntityTransfer = $this->buildQueryFromCriteria($query)->find();
        $spyReclamationEntityTransfer = $spyReclamationsEntityTransfer[0];

        $query = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        $createdOrdersEntityTransfer = $this->buildQueryFromCriteria($query)->find();
        if ($createdOrdersEntityTransfer) {
            $spyReclamationEntityTransfer->setSpySalesOrders(new ArrayObject($createdOrdersEntityTransfer));
        }

        return $this->getMapper()->mapEntityTransferToReclamationTransfer($spyReclamationEntityTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
