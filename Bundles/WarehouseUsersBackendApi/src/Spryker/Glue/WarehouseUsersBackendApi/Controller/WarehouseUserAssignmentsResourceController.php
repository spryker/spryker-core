<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiFactory getFactory()
 */
class WarehouseUserAssignmentsResourceController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createWarehouseUserAssignmentReader()
            ->getWarehouseUserAssignmentCollection($glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createWarehouseUserAssignmentReader()
            ->getWarehouseUserAssignment($glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentCreator()
            ->createWarehouseUserAssignment(
                $warehouseUserAssignmentsRestAttributesTransfer,
                $glueRequestTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentUpdater()
            ->updateWarehouseUserAssignment(
                $warehouseUserAssignmentsRestAttributesTransfer,
                $glueRequestTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createWarehouseUserAssignmentDeleter()
            ->deleteWarehouseUserAssignment($glueRequestTransfer);
    }
}
