<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class FileSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    /**
     * @param \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface $fileManagerFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
            $this->processFileTransfer($fileTransfer);
        }

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getFiles()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function processFileTransfer(FileTransfer $fileTransfer): void
    {
        $fileUploadTransfer = $fileTransfer->getFileUploadOrFail();
        $fileManagerDataTransfer = $this->fileManagerFacade->saveFile(
            $this->createFileManagerDataTransfer($fileTransfer, $fileUploadTransfer),
        );

        $fileTransfer->setIdFile($fileManagerDataTransfer->getFile() ? $fileManagerDataTransfer->getFile()->getIdFile() : null);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createFileManagerDataTransfer(FileTransfer $fileTransfer, FileUploadTransfer $fileUploadTransfer): FileManagerDataTransfer
    {
        return (new FileManagerDataTransfer())
            ->setFile($this->createFileTransfer($fileUploadTransfer))
            ->setFileInfo($this->createFileInfoTransfer($fileUploadTransfer))
            ->setContent($this->decodeFileContent($fileTransfer->getEncodedContentOrFail()));
    }

    /**
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(FileUploadTransfer $fileUploadTransfer): FileTransfer
    {
        return (new FileTransfer())
            ->setFileName($fileUploadTransfer->getClientOriginalName())
            ->setFileUpload($fileUploadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(FileUploadTransfer $fileUploadTransfer): FileInfoTransfer
    {
        return (new FileInfoTransfer())
            ->setType($fileUploadTransfer->getMimeTypeName())
            ->setExtension($fileUploadTransfer->getClientOriginalExtension())
            ->setSize($fileUploadTransfer->getSize())
            ->setStorageName($this->sspInquiryManagementConfig->getStorageName());
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
