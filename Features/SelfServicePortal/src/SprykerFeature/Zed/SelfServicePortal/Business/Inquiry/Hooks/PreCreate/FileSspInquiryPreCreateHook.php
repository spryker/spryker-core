<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    public function __construct(
        protected FileManagerFacadeInterface $fileManagerFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
            $this->processFileTransfer($fileTransfer);
        }

        return $sspInquiryTransfer;
    }

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getFiles()->count() > 0;
    }

    protected function processFileTransfer(FileTransfer $fileTransfer): void
    {
        $fileUploadTransfer = $fileTransfer->getFileUploadOrFail();
        $fileManagerDataTransfer = $this->fileManagerFacade->saveFile(
            $this->createFileManagerDataTransfer($fileTransfer, $fileUploadTransfer),
        );

        $fileTransfer->setIdFile($fileManagerDataTransfer->getFile() ? $fileManagerDataTransfer->getFile()->getIdFile() : null);
    }

    protected function createFileManagerDataTransfer(FileTransfer $fileTransfer, FileUploadTransfer $fileUploadTransfer): FileManagerDataTransfer
    {
        return (new FileManagerDataTransfer())
            ->setFile($this->createFileTransfer($fileUploadTransfer))
            ->setFileInfo($this->createFileInfoTransfer($fileUploadTransfer))
            ->setContent($this->decodeFileContent($fileTransfer->getEncodedContentOrFail()));
    }

    protected function createFileTransfer(FileUploadTransfer $fileUploadTransfer): FileTransfer
    {
        return (new FileTransfer())
            ->setFileName($fileUploadTransfer->getClientOriginalName())
            ->setFileUpload($fileUploadTransfer);
    }

    protected function createFileInfoTransfer(FileUploadTransfer $fileUploadTransfer): FileInfoTransfer
    {
        return (new FileInfoTransfer())
            ->setType($fileUploadTransfer->getMimeTypeName())
            ->setExtension($fileUploadTransfer->getClientOriginalExtension())
            ->setSize($fileUploadTransfer->getSize())
            ->setStorageName($this->selfServicePortalConfig->getInquiryFileUploadStorageName());
    }

    protected function decodeFileContent(string $encodedContent): string
    {
        return gzdecode(base64_decode($encodedContent)) ?: '';
    }
}
