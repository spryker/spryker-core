<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;

interface FileUploadMapperInterface
{
    /**
     * @param array<\Symfony\Component\HttpFoundation\File\UploadedFile> $uploadedFiles
     *
     * @return array<\Generated\Shared\Transfer\FileUploadTransfer>
     */
    public function mapUploadedFilesToFileUploadTransfers(array $uploadedFiles): array;

    public function mapFileUploadTransferToFileTransfer(FileUploadTransfer $fileUploadTransfer): FileTransfer;

    public function mapFileTransferToFileManagerDataTransfer(FileTransfer $fileTransfer): FileManagerDataTransfer;
}
