<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissionsAction(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        return $this->getFacade()->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);
    }
}
