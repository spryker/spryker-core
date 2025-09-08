<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileSspModelWriter implements FileSspModelWriterInterface
{
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SelfServicePortalConfig $config
    ) {
    }

    public function createFile(SspModelTransfer $sspModelTransfer): SspModelTransfer
    {
        $fileTransfer = $sspModelTransfer->getImage();

        if (!$fileTransfer) {
            return $sspModelTransfer;
        }

        if (!$fileTransfer->getFileUpload()) {
            return $sspModelTransfer;
        }

        $fileManagerDataTransfer = $this->saveImageFile($fileTransfer);

        return $sspModelTransfer->setImage($fileManagerDataTransfer->getFileOrFail()->setFileContent(null));
    }

    public function updateFile(SspModelTransfer $sspModelTransfer): SspModelTransfer
    {
        $fileTransfer = $sspModelTransfer->getImage();

        if (!$fileTransfer) {
            return $sspModelTransfer;
        }

        if (!$fileTransfer->getFileUpload()) {
            return $sspModelTransfer;
        }

        $fileManagerDataTransfer = $this->saveImageFile($fileTransfer);

        return $sspModelTransfer->setImage($fileManagerDataTransfer->getFileOrFail()->setFileContent(null));
    }

    protected function saveImageFile(FileTransfer $fileTransfer): FileManagerDataTransfer
    {
        $fileUploadTransfer = $fileTransfer->getFileUploadOrFail();

        $content = $fileTransfer->getFileContent() ?? gzdecode(base64_decode($fileTransfer->getEncodedContentOrFail())) ?: '';

        $fileManagerDataTransfer = (new FileManagerDataTransfer())
            ->setFile((new FileTransfer())->setFileName($fileUploadTransfer->getClientOriginalName()))
            ->setFileInfo(
                (new FileInfoTransfer())
                    ->setType($fileUploadTransfer->getMimeTypeName())
                    ->setExtension($fileUploadTransfer->getClientOriginalExtension())
                    ->setSize($fileUploadTransfer->getSize())
                    ->setStorageName($this->config->getModelStorageName()),
            )
            ->setContent($content);

        return $this->fileManagerFacade->saveFile($fileManagerDataTransfer);
    }
}
