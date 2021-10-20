<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
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
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @throws \Exception
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     *
     * @return string
     */
    protected function getFileContent(FileUploadTransfer $fileUploadTransfer)
    {
        $realPath = $fileUploadTransfer->getRealPath();

        if ($realPath === null) {
            throw new Exception('Real path not found');
        }

        $fileContent = file_get_contents($realPath);

        if ($fileContent === false) {
            throw new FileNotFoundException($realPath);
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

        if ($fileTransfer->getFileUpload() !== null) {
            $fileManagerDataTransfer->setContent(
                $this->getFileContent($fileTransfer->getFileUpload()),
            );
        }

        $fileManagerDataTransfer->setFileLocalizedAttributes($fileTransfer->getLocalizedAttributes());

        return $fileManagerDataTransfer;
    }
}
