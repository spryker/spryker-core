<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationPersistenceFactory getFactory()
 */
class SalesReclamationEntityManager extends AbstractEntityManager implements SalesReclamationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function saveReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $reclamationTransfer->requireIsOpen();

        $salesReclamationEntity = $this->getMapper()
            ->mapReclamationTransferToEntity($reclamationTransfer, new SpySalesReclamation());

        $salesReclamationEntity->save();
        $reclamationTransfer->setIdSalesReclamation($salesReclamationEntity->getIdSalesReclamation());

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer[]
     */
    public function saveReclamationItems(ReclamationTransfer $reclamationTransfer): array
    {
        $reclamationTransfer->requireReclamationItems();

        $reclamationItemsTransfer = $reclamationTransfer->getReclamationItems()->getArrayCopy();

        foreach ($reclamationItemsTransfer as $reclamationItemTransfer) {
            $reclamationItemTransfer = $this->saveReclamationItem($reclamationItemTransfer);
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        return $reclamationItemsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function saveReclamationItem(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        $reclamationItemTransfer
            ->requireFkSalesReclamation()
            ->requireOrderItem();

        $salesReclamationItemEntity = $this->getMapper()
            ->mapReclamationItemTransferToEntity($reclamationItemTransfer, new SpySalesReclamationItem());

        $salesReclamationItemEntity->save();
        $reclamationItemTransfer->setIdSalesReclamationItem($salesReclamationItemEntity->getIdSalesReclamationItem());

        return $reclamationItemTransfer;
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
