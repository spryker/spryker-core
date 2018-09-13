<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationPersistenceFactory getFactory()
 */
class SalesReclamationEntityManager extends AbstractEntityManager implements SalesReclamationEntityManagerInterface
{
    /**
     * @see \Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap::COL_STATE_OPEN
     */
    public const RECLAMATION_STATE_OPEN = 'Open';

    /**
     * @see \Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationItemTableMap::COL_STATE_OPEN
     */
    public const RECLAMATION_ITEM_STATE_OPEN = 'Open';

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function saveReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $reclamationTransfer
            ->requireState();

        $reclamationEntityTransfer = $this->getMapper()
            ->mapReclamationTransferToEntityTransfer($reclamationTransfer);

        $reclamationEntityTransfer = $this->save($reclamationEntityTransfer);
        $reclamationTransfer = $this->getMapper()
            ->mapEntityTransferToReclamationTransfer($reclamationEntityTransfer);

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
            $reclamationItemTransfer = $this->saveReclamationItem($reclamationTransfer, $reclamationItemTransfer);
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        return $reclamationItemsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    protected function saveReclamationItem(
        ReclamationTransfer $reclamationTransfer,
        ReclamationItemTransfer $reclamationItemTransfer
    ): ReclamationItemTransfer {
        $reclamationTransfer->requireIdSalesReclamation();
        $reclamationItemTransfer
            ->requireOrderItem()
            ->requireState();

        $reclamationItemEntityTransfer = $this->getMapper()
            ->mapReclamationItemTransferToEntityTransfer($reclamationItemTransfer);
        $reclamationItemEntityTransfer->setFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        $reclamationItemEntityTransfer = $this->save($reclamationItemEntityTransfer);

        return $this->getMapper()
            ->mapEntityTransferToReclamationItemTransfer($reclamationItemEntityTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
