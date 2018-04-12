<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\Base\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Exception\FileInfoNotFoundException;

class FileReader implements FileReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected $fileContent;

    /**
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     */
    public function __construct(FileFinderInterface $fileFinder, FileContentInterface $fileContent)
    {
        $this->fileFinder = $fileFinder;
        $this->fileContent = $fileContent;
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($idFileInfo)
    {
        $fileInfo = $this->fileFinder->getFileInfo($idFileInfo);

        if ($fileInfo === null) {
            return new FileManagerReadResponseTransfer();
        }

        return $this->createResponseTransfer($fileInfo);
    }

    /**
     * @param int $idFile
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileInfoNotFoundException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readLatestByFileId($idFile)
    {
        $fileInfo = $this->fileFinder->getLatestFileInfoByFkFile($idFile);

        if ($fileInfo === null) {
            return new FileManagerReadResponseTransfer();
        }

        return $this->createResponseTransfer($fileInfo);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    protected function createResponseTransfer(SpyFileInfo $fileInfo)
    {
        $fileTransfer = $this->createFileTransfer($fileInfo->getFile());
        $fileInfoTransfer = $this->createFileInfoTransfer($fileInfo);

        $responseTransfer = new FileManagerReadResponseTransfer();
        $responseTransfer->setFile($fileTransfer);
        $responseTransfer->setFileInfo($fileInfoTransfer);

        $content = $this->fileContent->read($fileInfo->getStorageFileName());
        $responseTransfer->setContent($content);

        return $responseTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\Base\SpyFile $file
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(SpyFile $file)
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->fromArray($file->toArray());

        return $fileTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(SpyFileInfo $fileInfo)
    {
        $fileInfoTransfer = new FileInfoTransfer();
        $fileInfoTransfer->fromArray($fileInfo->toArray());

        return $fileInfoTransfer;
    }
}
