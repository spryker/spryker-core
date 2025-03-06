<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Mapper;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;

interface FileAttachmentMapperInterface
{
    /**
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function mapFormDataToFileAttachmentCollectionTransfer(
        array $formData,
        FileManagerDataTransfer $fileManagerDataTransfer,
        int $idFile
    ): FileAttachmentCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, array<int>>
     */
    public function mapFileAttachmentCollectionTransferToFormData(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array;
}
