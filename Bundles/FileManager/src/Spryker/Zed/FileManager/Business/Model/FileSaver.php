<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileSaver implements FileSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    const FILE_NAME_PATTERN = '%u%s%s.%s';

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected $fileContent;

    /**
     * @var \Spryker\Zed\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaverInterface
     */
    protected $attributesSaver;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\FileManager\Business\Model\FileVersionInterface $fileVersion
     * @param \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface $fileLoader
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     * @param \Spryker\Zed\FileManager\Business\Model\FileLocalizedAttributesSaverInterface $attributesSaver
     * @param \Spryker\Zed\FileManager\FileManagerConfig $config
     */
    public function __construct(
        FileManagerQueryContainerInterface $queryContainer,
        FileVersionInterface $fileVersion,
        FileLoaderInterface $fileLoader,
        FileContentInterface $fileContent,
        FileLocalizedAttributesSaverInterface $attributesSaver,
        FileManagerConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->fileVersion = $fileVersion;
        $this->fileLoader = $fileLoader;
        $this->fileContent = $fileContent;
        $this->config = $config;
        $this->attributesSaver = $attributesSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        if ($this->fileExists($saveRequestTransfer)) {
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
        $file = $this->fileLoader->getFile($saveRequestTransfer->getFile()->getIdFile());

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
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    protected function saveFile(SpyFile $file, FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        return $this->handleDatabaseTransaction(
            function () use ($file, $saveRequestTransfer) {
                $file->fromArray($saveRequestTransfer->getFile()->toArray());

                $fileInfo = $this->createFileInfo($saveRequestTransfer);
                $this->addFileInfoToFile($file, $fileInfo);

                $savedRowsCount = $file->save();
                $this->attributesSaver->saveLocalizedFileAttributes($file, $saveRequestTransfer);
                $this->saveContent($saveRequestTransfer, $file, $fileInfo);

                return $savedRowsCount;
            }, $this->queryContainer->getConnection()
        );
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo|null $fileInfo
     *
     * @return void
     */
    protected function addFileInfoToFile(SpyFile $file, SpyFileInfo $fileInfo = null)
    {
        if ($fileInfo !== null) {
            $file->addSpyFileInfo($fileInfo);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo|null $fileInfo
     *
     * @return void
     */
    protected function saveContent(FileManagerSaveRequestTransfer $saveRequestTransfer, SpyFile $file, SpyFileInfo $fileInfo = null)
    {
        if ($saveRequestTransfer->getContent() !== null || $fileInfo !== null) {
            $newFileName = $this->fileLoader->buildFilename($file);
            $this->fileContent->save($newFileName, $saveRequestTransfer->getContent());
            $this->addStorageInfo($fileInfo, $newFileName);
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param string $newFileName
     *
     * @return void
     */
    protected function addStorageInfo(SpyFileInfo $fileInfo, string $newFileName)
    {
        $fileInfo->reload();
        $fileInfo->setStorageName($this->config->getStorageName());
        $fileInfo->setStorageFileName($newFileName);

        $fileInfo->save();
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function createFileInfo(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        if ($saveRequestTransfer->getContent() === null) {
            return null;
        }

        $fileInfoTransfer = $saveRequestTransfer->getFileInfo();
        $fileInfo = new SpyFileInfo();
        $fileInfo->fromArray($fileInfoTransfer->toArray());

        $nextVersion = $this->fileVersion->getNextVersionNumber($fileInfoTransfer->getFkFile());
        $newVersionName = $this->fileVersion->getNextVersionName($nextVersion);
        $fileInfo->setVersion($nextVersion);
        $fileInfo->setVersionName($newVersionName);

        return $fileInfo;
    }

    /**
     * @param int $idFile
     * @param string $versionName
     * @param string $fileExtension
     *
     * @return string
     */
    protected function getNewFileName(int $idFile, string $versionName, string $fileExtension): string
    {
        $fileNameVersionDelimiter = $this->config->getFileNameVersionDelimiter();

        $newFileName = sprintf(
            static::FILE_NAME_PATTERN,
            $idFile,
            $fileNameVersionDelimiter,
            $versionName,
            $fileExtension
        );

        return $newFileName;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return bool
     */
    protected function fileExists(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $idFile = $saveRequestTransfer->getFile()->getIdFile();

        if ($idFile === null) {
            return false;
        }

        $file = $this->fileLoader->getFile($idFile);

        return $file !== null;
    }
}
