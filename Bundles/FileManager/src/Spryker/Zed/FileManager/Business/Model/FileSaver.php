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
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileSaver implements FileSaverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * @var FileContentInterface
     */
    protected $fileContent;
    /**
     * @var FileManagerConfig
     */
    protected $config;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     * @param FileVersionInterface $fileVersion
     * @param FileFinderInterface $fileFinder
     * @param FileContentInterface $fileContent
     * @param FileManagerConfig $config
     */
    public function __construct(
        FileManagerQueryContainerInterface $queryContainer,
        FileVersionInterface $fileVersion,
        FileFinderInterface $fileFinder,
        FileContentInterface $fileContent,
        FileManagerConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->fileVersion = $fileVersion;
        $this->fileFinder = $fileFinder;
        $this->fileContent = $fileContent;
        $this->config = $config;
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
     * @throws Exception
     */
    protected function saveFile(SpyFile $file, FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        try {
            $file->fromArray($saveRequestTransfer->getFile()->toArray());
            $fileInfo = $this->createFileInfo($saveRequestTransfer->getFileInfo());
            $file->addSpyFileInfo($fileInfo);

            $savedRowsCount = $file->save();

            $newFileName = $this->getNewFileName($file->getFileName(), $fileInfo->getVersionName());
            $this->fileContent->save($saveRequestTransfer->getTempFilePath(), $newFileName);
            $this->addStorageInfo($fileInfo, $newFileName);

            $this->queryContainer->getConnection()->commit();

            return $savedRowsCount;
        } catch (Exception $exception) {
            $this->queryContainer->getConnection()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyFileInfo $fileInfo
     * @param string $newFileName
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function addStorageInfo(SpyFileInfo $fileInfo, string $newFileName)
    {
        $fileInfo->reload();
        $fileInfo->setStorageName($this->config->getStorageName());
        $fileInfo->setStorageFileName($newFileName);

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
     * @param string $fileName
     * @param string $versionName
     * @return string
     */
    protected function getNewFileName(string $fileName, string $versionName)
    {
        return $fileName . $versionName;
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

        $file = $this->fileFinder->getFile($fileId);

        return $file !== null;
    }
}
