<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface;

class WarehouseUserAssignmentReader implements WarehouseUserAssignmentReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface
     */
    protected PickingListsBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade
     */
    public function __construct(PickingListsBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade)
    {
        $this->warehouseUserFacade = $warehouseUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(UserTransfer $userTransfer): WarehouseUserAssignmentCollectionTransfer
    {
        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer($userTransfer);

        return $this
            ->warehouseUserFacade
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCriteriaTransfer(UserTransfer $userTransfer): WarehouseUserAssignmentCriteriaTransfer
    {
        $warehouseUserAssignmentConditions = (new WarehouseUserAssignmentConditionsTransfer())
            ->addUserUuid($userTransfer->getUuidOrFail())
            ->setIsActive(true);

        return (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditions);
    }
}
