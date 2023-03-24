<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Dependency\Facade;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;

class PickingListToWarehouseUserFacadeBridge implements PickingListToWarehouseUserInterface
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
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCollectionTransfer {
        return $this
            ->warehouseUserFacade
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);
    }
}
