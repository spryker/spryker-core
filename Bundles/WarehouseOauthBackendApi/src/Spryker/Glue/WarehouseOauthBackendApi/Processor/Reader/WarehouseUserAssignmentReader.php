<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeInterface;

class WarehouseUserAssignmentReader implements WarehouseUserAssignmentReaderInterface
{
    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeInterface
     */
    protected WarehouseOauthBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade;

    /**
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade
     */
    public function __construct(WarehouseOauthBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade)
    {
        $this->warehouseUserFacade = $warehouseUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer|null
     */
    public function findActiveWarehouseUserAssignment(GlueRequestTransfer $glueRequestTransfer): ?WarehouseUserAssignmentTransfer
    {
        $userUuid = $glueRequestTransfer->getRequestUserOrFail()->getNaturalIdentifier();

        if (!$userUuid) {
            return null;
        }

        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())
            ->addUserUuid($userUuid)
            ->setIsActive(true);

        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        return $this->warehouseUserFacade
            ->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer)
            ->getWarehouseUserAssignments()
            ->getIterator()
            ->current();
    }
}
