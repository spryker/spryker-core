<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage\Storage;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToStorageClientInterface;
use Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceInterface;
use Spryker\Shared\CategoryImageStorage\CategoryImageStorageConfig;

class CategoryImageStorageReader implements CategoryImageStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        CategoryImageStorageToSynchronizationServiceInterface $synchronizationService,
        CategoryImageStorageToStorageClientInterface $storageClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
    }

    /**
     * @deprecated Use `findCategoryImageStorageItemData()` instead.
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    public function findCategoryImageStorage(int $idCategory, string $localeName): ?CategoryImageSetCollectionStorageTransfer
    {
        $key = $this->generateKey($idCategory, $localeName);
        $categoryImageStorageData = $this->storageClient->get($key);
        if (!$categoryImageStorageData) {
            return null;
        }

        return $this->mapToCategoryImageStorage($categoryImageStorageData);
    }

    /**
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer|null
     */
    public function findCategoryImageStorageItemData(int $idCategory, string $localeName): ?CategoryImageStorageItemDataTransfer
    {
        $key = $this->generateKey($idCategory, $localeName);
        $categoryImageStorageData = $this->storageClient->get($key);

        if (!$categoryImageStorageData) {
            return null;
        }

        return $this->mapToCategoryImageStorageItemData($categoryImageStorageData);
    }

    /**
     * @deprecated Use `mapToCategoryImageStorageItemData()` instead.
     *
     * @param array $categoryImageStorageData
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer
     */
    protected function mapToCategoryImageStorage(array $categoryImageStorageData): CategoryImageSetCollectionStorageTransfer
    {
        return (new CategoryImageSetCollectionStorageTransfer())
            ->fromArray($categoryImageStorageData, true);
    }

    /**
     * @param array $categoryImageStorageData
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer
     */
    protected function mapToCategoryImageStorageItemData(array $categoryImageStorageData): CategoryImageStorageItemDataTransfer
    {
        return (new CategoryImageStorageItemDataTransfer())
            ->fromArray($categoryImageStorageData, true);
    }

    /**
     * @param int $idCategory
     * @param string $localName
     *
     * @return string
     */
    protected function generateKey(int $idCategory, string $localName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setLocale($localName)
            ->setReference((string)$idCategory);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CategoryImageStorageConfig::CATEGORY_IMAGE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
