<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\SalesReclamation\Persistence\Mapper\SalesReclamationMapperInterface;

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
        $reclamationTransfer
            ->requireStatus()
            ->requireReclamationItems();
        $reclamationItemsTransfer = $reclamationTransfer->getReclamationItems()->getArrayCopy();

        $reclamationEntityTransfer = $this->getMapper()
            ->mapReclamationTransferToEntityTransfer($reclamationTransfer);

        /** @var \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer $reclamationEntityTransfer */
        $reclamationEntityTransfer = $this->save($reclamationEntityTransfer);
        $reclamationTransfer = $this->getMapper()
            ->mapEntityTransferToReclamationTransfer($reclamationEntityTransfer);

        foreach ($reclamationItemsTransfer as $reclamationItemTransfer) {
            $reclamationItemTransfer = $this->saveReclamationItem($reclamationTransfer, $reclamationItemTransfer);
            $reclamationTransfer->addReclamationItem($reclamationItemTransfer);
        }

        return $reclamationTransfer;
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
            ->requireStatus();

        $reclamationItemEntityTransfer = $this->getMapper()
            ->mapReclamationItemTransferToEntityTransfer($reclamationItemTransfer);
        $reclamationItemEntityTransfer->setFkSalesReclamation($reclamationTransfer->getIdSalesReclamation());
        /** @var \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer */
        $reclamationItemEntityTransfer = $this->save($reclamationItemEntityTransfer);

        return $this->getMapper()
            ->mapEntityTransferToReclamationItemTransfer($reclamationItemEntityTransfer);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Persistence\Mapper\SalesReclamationMapperInterface
     */
    protected function getMapper(): SalesReclamationMapperInterface
    {
        return $this->getFactory()->createSalesReclamationMapper();
    }
}
