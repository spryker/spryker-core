<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;

interface SspFileManagementEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer;
}
