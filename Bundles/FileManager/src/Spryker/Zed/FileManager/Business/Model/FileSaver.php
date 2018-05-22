<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Shared\FileManager\FileManagerConstants;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileSaver implements FileSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    const FILE_NAME_PATTERN = '%u%s%s.%s';
    const DEFAULT_FILENAME = 'file';

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
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return int
     */
    public function save(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        if ($this->fileExists($fileManagerDataTransfer)) {
            return $this->update($fileManagerDataTransfer);
        }

        return $this->create($fileManagerDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return int
     */
    protected function update(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $file = $this->fileLoader->getFile($fileManagerDataTransfer->getFile()->getIdFile());

        return $this->saveFile($file, $fileManagerDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return int
     */
    protected function create(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $file = new SpyFile();

        return $this->saveFile($file, $fileManagerDataTransfer);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return int
     */
    protected function saveFile(SpyFile $file, FileManagerDataTransfer $fileManagerDataTransfer)
    {
        return $this->handleDatabaseTransaction(
            function () use ($file, $fileManagerDataTransfer) {
                $file->fromArray($fileManagerDataTransfer->getFile()->toArray());
                $file->setFileName($this->sanitizeFileName($file->getFileName()));

                $fileInfo = $this->createFileInfo($fileManagerDataTransfer);
                $this->addFileInfoToFile($file, $fileInfo);

                $savedRowsCount = $file->save();
                $this->attributesSaver->saveLocalizedFileAttributes($file, $fileManagerDataTransfer);
                $this->saveContent($fileManagerDataTransfer, $file, $fileInfo);

                return $savedRowsCount;
            },
            $this->queryContainer->getConnection()
        );
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function sanitizeFileName($fileName)
    {
        $fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
        $fileName = mb_ereg_replace("([\.]{2,})", '', $fileName);
        $fileName = preg_replace("/\s+/", ' ', $fileName);
        $fileName = trim($fileName);

        if (!strlen($fileName) || $fileName === '.') {
            $fileName = static::DEFAULT_FILENAME;
        }

        return $fileName;
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
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo|null $fileInfo
     *
     * @return void
     */
    protected function saveContent(FileManagerDataTransfer $fileManagerDataTransfer, SpyFile $file, SpyFileInfo $fileInfo = null)
    {
        if ($fileManagerDataTransfer->getContent() !== null || $fileInfo !== null) {
            $fileTransfer = new FileTransfer();
            $fileTransfer->setFileName($this->fileLoader->buildFilename($fileInfo));
            $fileTransfer->setFileContent($fileManagerDataTransfer->getContent());
            $this->fileContent->save($fileTransfer);
            $this->addStorageInfo($fileInfo, $fileTransfer->getFileName());
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
        $fileInfo->setStorageName($this->config->getStorageName());
        $fileInfo->setStorageFileName($newFileName);

        $fileInfo->save();
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function createFileInfo(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        if ($fileManagerDataTransfer->getContent() === null) {
            return null;
        }

        $fileInfoTransfer = $fileManagerDataTransfer->getFileInfo();
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
            FileManagerConstants::FILE_NAME_VERSION_DELIMITER,
            $versionName,
            $fileExtension
        );

        return $newFileName;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return bool
     */
    protected function fileExists(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $idFile = $fileManagerDataTransfer->getFile()->getIdFile();

        if ($idFile === null) {
            return false;
        }

        $file = $this->fileLoader->getFile($idFile);

        return $file !== null;
    }
}
