<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspFileManagement;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerFeature\Client\SspFileManagement\SspFileManagementFactory getFactory()
 */
class SspFileManagementClient extends AbstractClient implements SspFileManagementClientInterface
{
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
            ->createZedSspFileManagementStub()
            ->getFileAttachmentFileCollectionAccordingToPermissions($fileAttachmentFileCriteriaTransfer);
    }
}
