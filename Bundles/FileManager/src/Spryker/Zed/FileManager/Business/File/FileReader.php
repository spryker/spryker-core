<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

use ArrayObject;
use Generated\Shared\Transfer\FileManagerDataCollectionTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileContent\FileContentInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

class FileReader implements FileReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
     */
    protected $fileContent;

    /**
     * @var list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FileManagerDataCollectionExpanderPluginInterface>
     */
    protected array $fileManagerDataCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface $repository
     * @param \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface $fileContent
     * @param list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FileManagerDataCollectionExpanderPluginInterface> $fileManagerDataCollectionExpanderPlugins
     */
    public function __construct(
        FileManagerRepositoryInterface $repository,
        FileContentInterface $fileContent,
        array $fileManagerDataCollectionExpanderPlugins
    ) {
        $this->repository = $repository;
        $this->fileContent = $fileContent;
        $this->fileManagerDataCollectionExpanderPlugins = $fileManagerDataCollectionExpanderPlugins;
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFileByIdFile(int $idFile)
    {
        $fileTransfer = $this->repository->getFileByIdFile($idFile);

        if ($fileTransfer === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileTransfer);
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFileByIdFileInfo(int $idFileInfo)
    {
        $fileTransfer = $this->repository->getFileByIdFileInfo($idFileInfo);

        if ($fileTransfer === null) {
            return new FileManagerDataTransfer();
        }

        return $this->createResponseTransfer($fileTransfer, $idFileInfo);
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestByFileId(int $idFile)
    {
        $fileTransfer = new FileTransfer();
        $fileInfoTransfer = $this->repository->getLatestFileInfoByIdFile($idFile);

        if ($fileInfoTransfer === null) {
            return new FileManagerDataTransfer();
        }

        $fileTransfer->addFileInfo($fileInfoTransfer);

        return $this->createResponseTransfer($fileTransfer);
    }

    /**
     * @param array<int> $idFiles
     *
     * @return array<\Generated\Shared\Transfer\FileManagerDataTransfer>
     */
    public function getFilesByIds(array $idFiles): array
    {
        $fileTransfers = $this->repository->getFilesByIds($idFiles);

        if (!$fileTransfers) {
            return [];
        }

        $fileManagerDataTransfers = [];

        foreach ($fileTransfers as $fileTransfer) {
            $fileManagerDataTransfers[] = $this->createFileManagerDataTransfer($fileTransfer);
        }

        $fileManagerDataCollectionTransfer = new FileManagerDataCollectionTransfer();
        $fileManagerDataCollectionTransfer->setFileManagerDataItems(new ArrayObject($fileManagerDataTransfers));

        $fileManagerDataCollectionTransfer = $this->executeFileManagerDataCollectionExpanderPlugins($fileManagerDataCollectionTransfer);

        return $fileManagerDataCollectionTransfer->getFileManagerDataItems()->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int|null $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createResponseTransfer(FileTransfer $fileTransfer, ?int $idFileInfo = null)
    {
        $fileManagerDataTransfer = $this->createFileManagerDataTransfer($fileTransfer, $idFileInfo);

        $fileManagerDataCollectionTransfer = new FileManagerDataCollectionTransfer();
        $fileManagerDataCollectionTransfer->addFileManagerDataItem($fileManagerDataTransfer);

        $fileManagerDataCollectionTransfer = $this->executeFileManagerDataCollectionExpanderPlugins($fileManagerDataCollectionTransfer);

        return $fileManagerDataCollectionTransfer->getFileManagerDataItems()->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int|null $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer|null
     */
    protected function getRequestedFileInfo(FileTransfer $fileTransfer, ?int $idFileInfo = null)
    {
        if ($idFileInfo === null) {
            return $fileTransfer->getFileInfo()[0] ?? null;
        }

        foreach ($fileTransfer->getFileInfo() as $fileInfoTransfer) {
            if ($fileInfoTransfer->getIdFileInfo() === $idFileInfo) {
                return $fileInfoTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataCollectionTransfer $fileManagerDataCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataCollectionTransfer
     */
    protected function executeFileManagerDataCollectionExpanderPlugins(
        FileManagerDataCollectionTransfer $fileManagerDataCollectionTransfer
    ): FileManagerDataCollectionTransfer {
        foreach ($this->fileManagerDataCollectionExpanderPlugins as $fileManagerDataCollectionExpanderPlugin) {
            $fileManagerDataCollectionTransfer = $fileManagerDataCollectionExpanderPlugin->expand($fileManagerDataCollectionTransfer);
        }

        return $fileManagerDataCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param int|null $idFileInfo
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createFileManagerDataTransfer(FileTransfer $fileTransfer, ?int $idFileInfo = null): FileManagerDataTransfer
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFile($fileTransfer);
        $fileInfoTransfer = $this->getRequestedFileInfo($fileTransfer, $idFileInfo);
        $fileManagerDataTransfer->setFileInfo($fileInfoTransfer);

        if ($fileInfoTransfer !== null) {
            $fileManagerDataTransfer->setContent(
                $this->fileContent->read($fileInfoTransfer->getStorageFileNameOrFail()),
            );
        }

        return $fileManagerDataTransfer;
    }
}
