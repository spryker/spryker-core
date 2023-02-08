<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Dependency\Facade;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;

class WarehouseUserGuiToWarehouseUserFacadeBridge implements WarehouseUserGuiToWarehouseUserFacadeInterface
{
    /**
     * @var \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacadeInterface
     */
    protected $warehouseUserFacade;

    /**
     * @param \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacadeInterface $warehouseUserFacade
     */
    public function __construct($warehouseUserFacade)
    {
        $this->warehouseUserFacade = $warehouseUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function createWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        return $this->warehouseUserFacade->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        return $this->warehouseUserFacade->deleteWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionDeleteCriteriaTransfer);
    }
}
