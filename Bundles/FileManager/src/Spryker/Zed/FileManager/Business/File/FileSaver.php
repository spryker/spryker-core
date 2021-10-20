<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileContent\FileContentInterface;
use Spryker\Zed\FileManager\Business\FileLocalizedAttributes\FileLocalizedAttributesSaverInterface;
use Spryker\Zed\FileManager\Business\FileName\FileNameResolverTrait;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileSaver implements FileSaverInterface
{
    use FileNameResolverTrait;
    use TransactionTrait;

    /**
     * @var string
     */
    protected const FILE_NAME_PATTERN = '%u%s%s.%s';

    /**
     * @var string
     */
    protected const DEFAULT_FILENAME = 'file';

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManager\Business\File\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
     */
    protected $fileContent;

    /**
     * @var \Spryker\Zed\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\FileManager\Business\FileLocalizedAttributes\FileLocalizedAttributesSaverInterface
     */
    protected $attributesSaver;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManager\Business\File\FileVersionInterface $fileVersion
     * @param \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface $fileContent
     * @param \Spryker\Zed\FileManager\Business\FileLocalizedAttributes\FileLocalizedAttributesSaverInterface $attributesSaver
     * @param \Spryker\Zed\FileManager\FileManagerConfig $config
     */
    public function __construct(
        FileManagerEntityManagerInterface $entityManager,
        FileVersionInterface $fileVersion,
        FileContentInterface $fileContent,
        FileLocalizedAttributesSaverInterface $attributesSaver,
        FileManagerConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->fileVersion = $fileVersion;
        $this->fileContent = $fileContent;
        $this->config = $config;
        $this->attributesSaver = $attributesSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function save(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($fileManagerDataTransfer) {
            return $this->executeSaveTransaction($fileManagerDataTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function executeSaveTransaction(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $this->saveFile($fileManagerDataTransfer);
        $this->saveFileInfo($fileManagerDataTransfer);

        $this->attributesSaver->save($fileManagerDataTransfer);
        $this->saveContent($fileManagerDataTransfer);

        return $fileManagerDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function saveFile(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $this->prepareFileTransfer($fileManagerDataTransfer->getFileOrFail());
        $fileTransfer = $this->entityManager->saveFile($fileManagerDataTransfer->getFileOrFail());
        $fileManagerDataTransfer->setFile($fileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function saveFileInfo(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        if ($fileManagerDataTransfer->getContent() === null) {
            return;
        }

        $this->prepareFileInfoTransfer($fileManagerDataTransfer);
        $fileInfoTransfer = $this->entityManager->saveFileInfo($fileManagerDataTransfer->getFileInfoOrFail());
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function prepareFileTransfer(FileTransfer $fileTransfer)
    {
        $fileTransfer->setFileName(
            $this->sanitizeFileName($fileTransfer->getFileName())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function prepareFileInfoTransfer(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $fileTransfer = $fileManagerDataTransfer->getFileOrFail();
        $fileInfoTransfer = $fileManagerDataTransfer->getFileInfoOrFail();

        $nextVersion = $this->fileVersion->getNextVersionNumber($fileInfoTransfer->getFkFile());
        $nextVersionName = $this->fileVersion->getNextVersionName($nextVersion);

        $fileInfoTransfer->setFkFile($fileTransfer->getIdFile());
        $fileInfoTransfer->setVersion($nextVersion);
        $fileInfoTransfer->setVersionName($nextVersionName);
        $fileInfoTransfer->setStorageName($this->config->getStorageName());
        $fileInfoTransfer->setStorageFileName(
            $this->buildFilename($fileInfoTransfer, $fileTransfer->getFkFileDirectory())
        );
    }

    /**
     * @param string|null $fileName
     *
     * @return string
     */
    protected function sanitizeFileName(?string $fileName = null): string
    {
        $fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', (string)$fileName);
        $fileName = mb_ereg_replace("([\.]{2,})", '', (string)$fileName);
        $fileName = preg_replace("/\s+/", ' ', (string)$fileName);
        $fileName = trim((string)$fileName);

        if (!strlen($fileName) || $fileName === '.') {
            $fileName = static::DEFAULT_FILENAME;
        }

        return $fileName;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function saveContent(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        if ($fileManagerDataTransfer->getContent() !== null) {
            $fileTransfer = $fileManagerDataTransfer->getFile() ?? new FileTransfer();
            $fileTransfer->setFileContent($fileManagerDataTransfer->getContent());
            $fileTransfer->setFileName(
                $fileManagerDataTransfer->getFileInfoOrFail()->getStorageFileName()
            );
            $this->fileContent->save($fileTransfer);
        }
    }

    /**
     * @return string
     */
    protected function getFileNameVersionDelimiter()
    {
        return $this->config->getFileNameVersionDelimiter();
    }
}
