<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Business\Writer;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig;

class FileSspAssetWriter implements FileSspAssetWriterInterface
{
    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig $config
     */
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SspAssetManagementConfig $config
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
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

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function updateFile(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $fileTransfer = $sspAssetTransfer->getImage();

        if (!$fileTransfer) {
            return $sspAssetTransfer;
        }

        if ($fileTransfer->getDelete() && $fileTransfer->getIdFile()) {
            $sspAssetTransfer->setImage(null);
        }

        if (!$fileTransfer->getFileUpload()) {
            return $sspAssetTransfer;
        }

        $fileManagerDataTransfer = $this->saveImageFile($fileTransfer);

        return $sspAssetTransfer
            ->setImage($fileManagerDataTransfer->getFileOrFail()->setFileContent(null));
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function saveImageFile(FileTransfer $fileTransfer): FileManagerDataTransfer
    {
        $fileUploadTransfer = $fileTransfer->getFileUploadOrFail();

        $content = $fileTransfer->getFileContent() ?? $this->decodeFileContent($fileTransfer->getEncodedContentOrFail());

        $fileManagerDataTransfer = (new FileManagerDataTransfer())
            ->setFile((new FileTransfer())->setFileName($fileUploadTransfer->getClientOriginalName()))
            ->setFileInfo(
                (new FileInfoTransfer())
                    ->setType($fileUploadTransfer->getMimeTypeName())
                    ->setExtension($fileUploadTransfer->getClientOriginalExtension())
                    ->setSize($fileUploadTransfer->getSize())
                    ->setStorageName($this->config->getStorageName()),
            )
            ->setContent($content);

        return $this->fileManagerFacade->saveFile($fileManagerDataTransfer);
    }

    /**
     * @param string $encodedContent
     *
     * @return string
     */
    protected function decodeFileContent(string $encodedContent): string
    {
        return gzdecode(base64_decode($encodedContent)) ?: '';
    }
}
