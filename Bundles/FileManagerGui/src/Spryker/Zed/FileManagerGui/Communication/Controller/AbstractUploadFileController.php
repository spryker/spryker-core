<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

abstract class AbstractUploadFileController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    abstract protected function setFileName(FileTransfer $fileTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    abstract protected function createFileInfoTransfer(FileTransfer $fileTransfer);

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     *
     * @return string
     */
    protected function getFileContent(FileTransfer $fileTransfer)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $fileTransfer->getFileContent();
        $fileContent = file_get_contents($uploadedFile->getRealPath());

        if ($fileContent === false) {
            throw new FileNotFoundException($uploadedFile->getRealPath());
        }

        return $fileContent;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createFileManagerDataTransfer(FileTransfer $fileTransfer)
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $this->setFileName($fileTransfer);

        $fileManagerDataTransfer->setFile($fileTransfer);
        $fileManagerDataTransfer->setFileInfo($this->createFileInfoTransfer($fileTransfer));

        if ($fileTransfer->getFileContent() !== null) {
            $fileManagerDataTransfer->setContent($this->getFileContent($fileTransfer));
        }

        $fileManagerDataTransfer->setFileLocalizedAttributes($fileTransfer->getLocalizedAttributes());

        return $fileManagerDataTransfer;
    }
}
