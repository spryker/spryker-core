<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementBusinessFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface getEntityManager()
 */
class SspFileManagementFacade extends AbstractFacade implements SspFileManagementFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): FileAttachmentCollectionResponseTransfer {
        return $this->getFactory()
            ->createFileAttachmentDeleter()
            ->deleteFileAttachmentCollection($fileAttachmentCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function saveFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer {
        return $this->getFactory()
            ->createFileAttachmentSaver()
            ->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCollectionTransfer
    {
        return $this->getRepository()->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        return $this->getFactory()
            ->createFileReader()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);
    }
}
