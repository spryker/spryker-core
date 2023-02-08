<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\WarehouseUser\Business\WarehouseUserBusinessFactory getFactory()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface getEntityManager()
 */
class WarehouseUserFacade extends AbstractFacade implements WarehouseUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCollectionTransfer {
        return $this->getRepository()->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function createWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentCreator()
            ->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function updateWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentUpdater()
            ->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentDeleter()
            ->deleteWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionDeleteCriteriaTransfer);
    }
}
