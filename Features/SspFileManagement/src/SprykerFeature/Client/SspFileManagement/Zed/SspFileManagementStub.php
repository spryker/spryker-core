<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspFileManagement\Zed;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SspFileManagementStub implements SspFileManagementStubInterface
{
    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(protected ZedRequestClientInterface $zedRequestClient)
    {
    }

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\GatewayController::getFilesAccordingToPermissionsAction()
     *
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer {
        /** @var \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer $fileAttachmentFileCollectionTransfer */
        $fileAttachmentFileCollectionTransfer = $this->zedRequestClient->call(
            '/ssp-file-management/gateway/get-file-attachment-file-collection-according-to-permissions',
            $fileAttachmentFileCriteriaTransfer,
        );

        return $fileAttachmentFileCollectionTransfer;
    }
}
