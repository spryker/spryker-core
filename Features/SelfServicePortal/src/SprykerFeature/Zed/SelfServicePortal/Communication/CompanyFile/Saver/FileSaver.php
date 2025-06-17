<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver;

use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGeneratorInterface;

class FileSaver implements FileSaverInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapperInterface $fileUploadMapper
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGeneratorInterface $fileReferenceGenerator
     */
    public function __construct(
        protected FileUploadMapperInterface $fileUploadMapper,
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected FileReferenceGeneratorInterface $fileReferenceGenerator
    ) {
    }

    /**
     * @param array<\Symfony\Component\HttpFoundation\File\UploadedFile> $uploadedFiles
     *
     * @return void
     */
    public function saveFiles(array $uploadedFiles): void
    {
        $fileUploadTransfers = $this->fileUploadMapper->mapUploadedFilesToFileUploadTransfers($uploadedFiles);

        foreach ($fileUploadTransfers as $fileUploadTransfer) {
            $fileTransfer = $this->fileUploadMapper->mapFileUploadTransferToFileTransfer($fileUploadTransfer);
            $fileReference = $this->fileReferenceGenerator->generateFileReference($fileTransfer);
            $fileTransfer->setFileReference($fileReference);

            $fileManagerDataTransfer = $this->fileUploadMapper->mapFileTransferToFileManagerDataTransfer($fileTransfer);
            $this->fileManagerFacade->saveFile($fileManagerDataTransfer);
        }
    }
}
