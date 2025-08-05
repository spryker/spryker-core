<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileSspAssetWriter implements FileSspAssetWriterInterface
{
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SelfServicePortalConfig $config
    ) {
    }

    public function createFile(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $fileTransfer = $sspAssetTransfer->getImage();

        if (!$fileTransfer) {
            return $sspAssetTransfer;
        }

        if (!$fileTransfer->getFileUpload()) {
            return $sspAssetTransfer;
        }

        $fileManagerDataTransfer = $this->saveImageFile($fileTransfer);

        return $sspAssetTransfer->setImage($fileManagerDataTransfer->getFileOrFail()->setFileContent(null));
    }

    public function updateFile(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $fileTransfer = $sspAssetTransfer->getImage();

        if (!$fileTransfer) {
            return $sspAssetTransfer;
        }

        if ($fileTransfer->getToDelete() && $fileTransfer->getIdFile()) {
            $sspAssetTransfer->setImage(null);
        }

        if (!$fileTransfer->getFileUpload()) {
            return $sspAssetTransfer;
        }

        $fileManagerDataTransfer = $this->saveImageFile($fileTransfer);

        return $sspAssetTransfer
            ->setImage($fileManagerDataTransfer->getFileOrFail()->setFileContent(null));
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
                    ->setStorageName($this->config->getAssetImageFileUploadStorageName()),
            )
            ->setContent($content);

        return $this->fileManagerFacade->saveFile($fileManagerDataTransfer);
    }
}
