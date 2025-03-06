<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Plugin\FileManager;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileCollectionTransfer;
use Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 */
class FileAttachmentFilePreDeletePlugin extends AbstractPlugin implements FilePreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts file IDs from the provided `FileCollectionTransfer`.
     * - Deletes file attachments based on the extracted file IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    public function preDelete(FileCollectionTransfer $fileCollectionTransfer): FileCollectionTransfer
    {
        $fileIds = $this->extractFileIds($fileCollectionTransfer);

        $this->getFacade()->deleteFileAttachmentCollection(
            (new FileAttachmentCollectionDeleteCriteriaTransfer())->setFileIds($fileIds),
        );

        return $fileCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractFileIds(FileCollectionTransfer $fileCollectionTransfer): array
    {
        $fileIds = [];

        foreach ($fileCollectionTransfer->getFiles() as $fileTransfer) {
            $fileIds[] = $fileTransfer->getIdFileOrFail();
        }

        return $fileIds;
    }
}
