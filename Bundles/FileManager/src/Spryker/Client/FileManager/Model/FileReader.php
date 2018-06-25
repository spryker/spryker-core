<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager\Model;

use Generated\Shared\Transfer\FileInfoCollectionTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientInterface;
use Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileReader implements FileReaderInterface
{
    protected const ID_FILE = 'fk_file';
    protected const TYPE_FILE = 'file';

    /**
     * @var \Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected $keyGenerator;

    /**
     * @var \Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * FileReader constructor.
     *
     * @param \Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientInterface $storageClient
     * @param \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface $keyGenerator
     * @param \Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientInterface $localeClient
     */
    public function __construct(
        FileManagerToStorageClientInterface $storageClient,
        SynchronizationKeyGeneratorPluginInterface $keyGenerator,
        FileManagerToLocaleClientInterface $localeClient
    )
    {
        $this->storageClient = $storageClient;
        $this->keyGenerator = $keyGenerator;
        $this->localeClient = $localeClient;
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readLatestFileVersion($idFile)
    {
        $fileArray = $this->fetchFileFromStorage($idFile);
        $versions = $this->mapVersionsArrayToTransfer($fileArray);
        $file = $this->convertFileArrayToTransfer($fileArray);
        $requiredVersion = $this->getLatestVersion($versions);

        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileManagerDataTransfer->setFile($file);
        $fileManagerDataTransfer->setFileInfo($requiredVersion);

        return $fileManagerDataTransfer;
    }

    /**
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileInfoCollectionTransfer
     */
    public function getFileVersions(int $idFile)
    {
        $fileArray = $this->fetchFileFromStorage($idFile);

        return $this->mapVersionsArrayToTransfer($fileArray);
    }

    /**
     * @param int $idFile
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function fetchFileFromStorage(int $idFile)
    {
        $fileArray = $this->storageClient->get(
            $this->convertIdToKey($idFile)
        );

        if ($fileArray === null) {
            throw new NotFoundHttpException();
        }

        return $fileArray;
    }

    /**
     * @param \Generated\Shared\Transfer\FileInfoCollectionTransfer $collectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer|null
     */
    protected function getLatestVersion(FileInfoCollectionTransfer $collectionTransfer)
    {
        $versions = $collectionTransfer->getVersions();
        $latestVersion = $versions[count($versions) - 1];

        return $latestVersion;
    }

    /**
     * @param array $fileArray
     *
     * @return \Generated\Shared\Transfer\FileInfoCollectionTransfer
     */
    protected function mapVersionsArrayToTransfer(array $fileArray): FileInfoCollectionTransfer
    {
        $fileInfoCollection = new FileInfoCollectionTransfer();

        foreach ($fileArray['versions'] as $version) {
            $fileInfo = new FileInfoTransfer();
            $fileInfo->fromArray($version);

            $fileInfoCollection->addVersions($fileInfo);
        }

        return $fileInfoCollection;
    }

    /**
     * @param array $fileArray
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function convertFileArrayToTransfer(array $fileArray): FileTransfer
    {
        $transfer = new FileTransfer();
        $transfer->fromArray($fileArray, true);
        $transfer->setIdFile($fileArray[static::ID_FILE]);

        return $transfer;
    }

    /**
     * @param int $idFile
     *
     * @return string
     */
    protected function convertIdToKey(int $idFile): string
    {
        $this->keyGenerator->setResource(static::TYPE_FILE);

        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference((string)$idFile);
        $synchronizationDataTransfer->setLocale(
            $this->localeClient->getCurrentLocale()
        );

        return $this->keyGenerator->generateKey($synchronizationDataTransfer);
    }
}
