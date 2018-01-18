<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Exception;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\Cms\Persistence\SpyFile;
use Orm\Zed\Cms\Persistence\SpyFileInfo;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileSaver implements FileSaverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    protected $fileManagerService;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\FileManager\Business\Model\FileVersionInterface $fileVersion
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    public function __construct(
        FileManagerQueryContainerInterface $queryContainer,
        FileVersion $fileVersion,
        FileFinder $fileFinder,
        FileManagerServiceInterface $fileManagerService
    ) {
        $this->queryContainer = $queryContainer;
        $this->fileManagerService = $fileManagerService;
        $this->fileVersion = $fileVersion;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        if ($this->checkFileExists($saveRequestTransfer)) {
            return $this->update($saveRequestTransfer);
        }

        return $this->create($saveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    protected function update(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $file = $this->fileFinder->getFile($saveRequestTransfer->getFile()->getIdFile());

        return $this->saveFile($file, $saveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    protected function create(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $file = new SpyFile();

        return $this->saveFile($file, $saveRequestTransfer);
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    protected function saveFile(SpyFile $file, FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        try {
            $file->fromArray($saveRequestTransfer->getFile()->toArray());
            $fileInfo = $this->createFileInfo($saveRequestTransfer->getFileInfo());
            $file->addSpyFileInfo($fileInfo);

            $savedRowsCount = $file->save();

            $idStorage = $this->fileManagerService
                ->save($saveRequestTransfer->getTempFilePath());
            $this->addStorageId($fileInfo, $idStorage);

            $this->queryContainer->getConnection()->commit();

            return $savedRowsCount;
        } catch (Exception $exception) {
            $this->queryContainer->getConnection()->rollBack();
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyFileInfo $fileInfo
     * @param string $idStorage
     *
     * @return void
     */
    protected function addStorageId(SpyFileInfo $fileInfo, string $idStorage)
    {
        $fileInfo->reload();
        $fileInfo->setIdStorage($idStorage);

        $fileInfo->save();
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoTransfer $fileInfoTransfer
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    protected function createFileInfo(FileInfoTransfer $fileInfoTransfer)
    {
        $fileInfo = new SpyFileInfo();
        $fileInfo->fromArray($fileInfoTransfer->toArray());

        $newVersion = $this->fileVersion->getNewVersionNumber($fileInfoTransfer->getFkFile());
        $newVersionName = $this->fileVersion->getNewVersionName($newVersion);
        $fileInfo->setVersion($newVersion);
        $fileInfo->setVersionName($newVersionName);

        return $fileInfo;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return bool
     */
    protected function checkFileExists(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $fileId = $saveRequestTransfer->getFile()->getIdFile();

        if ($fileId == null) {
            return false;
        }

        $file = $this->queryContainer
            ->queryFileById($fileId)
            ->findOne();

        return $file !== null;
    }
}
