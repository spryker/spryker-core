<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;

interface FileAttachmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer
     * @param array<string, mixed> $formData
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer
     */
    public function mapFormDataToFileAttachmentCollectionTransfer(
        FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer,
        array $formData,
        int $idFile
    ): FileAttachmentCollectionRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer
     *
     * @return array<string, array<int>>
     */
    public function mapFileAttachmentCollectionTransferToFormData(FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer
     * @param array<string, mixed> $businessFormData
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer|null
     */
    public function mapFormDataToFileAttachmentCollectionDeleteCriteriaTransfer(
        FileAttachmentCollectionTransfer $currentFileAttachmentCollectionTransfer,
        array $businessFormData
    ): ?FileAttachmentCollectionDeleteCriteriaTransfer;
}
