<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManagerStorage\Storage;

use Generated\Shared\Transfer\FileStorageDataTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\FileManagerStorage\Dependency\Client\FileManagerStorageToStorageClientInterface;
use Spryker\Client\FileManagerStorage\Dependency\Service\FileManagerStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;

class FileManagerStorage implements FileManagerStorageInterface
{
    protected const RESOURCE_NAME = 'file';

    /**
     * @var \Spryker\Client\FileManagerStorage\Dependency\Client\FileManagerStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\FileManagerStorage\Dependency\Service\FileManagerStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @param \Spryker\Client\FileManagerStorage\Dependency\Client\FileManagerStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\FileManagerStorage\Dependency\Service\FileManagerStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(FileManagerStorageToStorageClientInterface $storageClient, FileManagerStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    public function findFileById($fileId, $localeName)
    {
        $storageKey = $this->generateKey((string)$fileId, $localeName);
        $fileContent = $this->storageClient->get($storageKey);

        $fileStorageDataTransfer = new FileStorageDataTransfer();
        $fileStorageDataTransfer->fromArray(($fileContent), true);

        return $fileStorageDataTransfer;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKey($keyName, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setStore($this->getStoreName());

        return $this->synchronizationService->getStorageKeyBuilder(static::RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }
}
